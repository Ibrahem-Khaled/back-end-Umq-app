<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\FcmAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Api\Auth\NotificationAdminController;

class OrderProductController extends Controller
{

    //------------------------------------------------------------------------ create order

    public function store(Request $request)
    {
        try {

            //check owner
            $isOwner = SecurityAbdallah::isUserOwner($request->user_id);
            if ($isOwner == 0) {
                return response([
                    'status' => 'failed - user_id not same current user id',
                    'code' => 0,
                ], 200);
            }

            $contCart = new CartProductController();

            //set default the request id
            $provider_id_selected = $request->provider_id;

            //case: provider id missed while there is only one provider in cart
            $missedProviderId = $request->provider_id == null;
            if ($missedProviderId) {
                $isOnlyOneProvider = $contCart->is_cart_have_only_one_provider($request);
                // dd($isOnlyOneProvider);
                if ($isOnlyOneProvider) {
                    $provider_id_selected = $contCart->get_cart_provider_id_first_one($request);
                    //dd($provider_id_selected);
                }
            }
            //dd($provider_id_selected);

            //case : no default provider found
            if ($provider_id_selected == null) {
                return response([
                    'status' => 'provider_id missed',
                    'msg' => "You Can Only Create Order With Only One Provider",
                    //, To can buy products from many provider you must create new order with new provider
                    'code' => 0,
                ], 200);
            }

            //total
            $price_product = $contCart->getTotalPriceProductsForSpecificProvider($request->user_id, $provider_id_selected);
            // dd($price_product );

            //check zero
            if ($price_product <= 0) {
                return response([
                    'status' => 'Price Product not valid zero. Add product to Cart first',
                    'code' => 0,
                ], 200);
            }

            //edit request
            /**
            "product_price": "60",
            "vat_price": "3",
            "shipment_price": "50",
            "net": "113",
             */
            $shipment_price = $this->getShipment();
            $vat = $this->getVatValue($price_product);
            $net_price = $this->getNetPrice($price_product);
            // $detail_order = $this>generateDetailOrderText($request );
            $request->merge(['product_price' => $price_product]);
            $request->merge(['vat_price' => $vat]);
            $request->merge(['shipment_price' => $shipment_price]);
            $request->merge(['net' => $net_price]);
            $request->merge(["provider_id" => $provider_id_selected]);
            // dd($request->provider_id );

            //save to order product
            $order_product = OrderProduct::create($request->all());
            $order_product->save();

            //save to order vendor
            $conVendor = new OrderVendorController();
            $conVendor->insertNewOrder($request, $order_product);

            //clear cart
            $cartCont = new CartProductController();
            $clearCart = $cartCont->clearMyCartForSpecificProvidier($request);

            //fcm: push
            $conFCM = new FcmAbdallah();
            $resFCM_customer = $conFCM->order_to_customer($request, $order_product);
            $resFCM_provider = $conFCM->order_to_provider($request, $order_product);
            $resFCM_admin = $conFCM->order_to_admin($request, $order_product);
            
            //set push fcm status
            $fcmSuccess = FcmAbdallah::$serverKeyEmptyMessage != $resFCM_customer;
            $order_product->fcm_status = $fcmSuccess;

            //fcm: update in db "order_product"
            $order_product->fcm_message_id = $resFCM_customer;
            $order_product->update();
            
            //resource object
            $this->resourceObject($order_product);

            return response([
                'status' => 'success',
                'code' => 1,
                'resFCM_customer' => $resFCM_customer,
                'resFCM_provider' => $resFCM_provider,
                'resFCM_admin' => $resFCM_admin,
                'clearCart' => $clearCart,
                'data' => $order_product,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    //------------------------------------------------------------------------ resource object

    public function resourceObject(OrderProduct $m)
    {
        $cont = new CityController();
        $m["city"] = $cont->getSingleObject($m->city_id);

        $cont = new UsersController();
        $m["user"] = $cont->getSingleObject($m->user_id);

        //product detail list
        $con = new OrderVendorController();
        $m["array_order_vendor"] = $con->getOrderProductDetail($m->id);
    }

    //----------------------------------------------------------------------- calculator

    //in saudi is 3%
    public function getVatValue($price_product)
    {
        return $price_product * 0.03;
    }

    public function getNetPrice($price_product)
    {

        $shipment_price = $this->getShipment();
        $vat = $this->getVatValue($price_product);

        return $shipment_price + $vat + $price_product;
    }

    public function getShipment()
    {
        /**
         * for this version 1
         * no need to calculate price of shipment,
         * just aramex will calculate by itself
         */
        return 0;
    }

    //------------------------------------------------------------------------ get list orders

    public function index(Request $request) {
                   // admin only allowed
                   $isAdmin = SecurityAbdallah::isUserAdmin();
                   $isProvider = SecurityAbdallah::isUserProvider();
                   if ($isAdmin  ) {
                       return $this->get_orders_by_admin( $request );
                   } else if( $isProvider ) {
                    return $this->get_orders_by_provider( $request );
                   } else {
                    return response([
                        'status' => 'Not Allowed Action',
                        'code' => 0,
                        'message' => "provider or admin only allowed to return the order data",
                    ], 200);
                   }
    }


    public function get_orders_by_admin(Request $request)
    {
        try {
 
            $query = OrderProduct::
                orderBy("id", "desc");

            //optional provider id
            if( $request->provider_id) {
                $query->where("provider_id", "=", $request->provider_id );
            }
            

            $order_product = $query->paginate($request->paginator, ['*'], 'page', $request->page);

            //resources
            foreach ($order_product as $m) {
                $this->resourceObject($m);
            }

            if ($order_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
    

    public function get_orders_by_provider(Request $request)
    {
        try {

                     //provider not allowed to filter by any provider
            if( $request->provider_id) {
                        return response([
                            'status' => 'failed user type provider, not allowed to filter by provider id',
                            'code' => 0,
                        ], 200);
            }
            
                    
 
            $user_id = SecurityAbdallah::getUserId();
            $conProvider = new ProviderController();
            $my_provider_id = $conProvider->getProviderIdOfThisUserId( $user_id);
            $query = OrderProduct::
                    where("provider_id", "=", $my_provider_id )
                ->orderBy("id", "desc");
 
            $order_product = $query->paginate($request->paginator, ['*'], 'page', $request->page);

            //resources
            foreach ($order_product as $m) {
                $this->resourceObject($m);
            }

            if ($order_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }


    //------------------------------------------------------------------------ v1

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    public function search($search, Request $request)
    {
        try {
            $searchQuery = trim($search);
            $requestData = ['id', 'user_id', 'payment_method', 'payment_online_id', 'shipment_id', 'address_detail', 'city_id', 'country', 'product_price', 'vat_price', 'shipment_price', 'net', 'status_order', 'product_detail', 'created', 'updated'];
            $order_product = OrderProduct::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($order_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order_product = OrderProduct::where('id', '=', $id)->first();
            if ($order_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_product,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_product data, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();

            $order_product = OrderProduct::find($id);

            $order_product->user_id = $input['user_id'];
            $order_product->payment_method = $input['payment_method'];
            $order_product->payment_online_id = $input['payment_online_id'];
            $order_product->shipment_id = $input['shipment_id'];
            $order_product->address_detail = $input['address_detail'];
            $order_product->city_id = $input['city_id'];
            $order_product->country = $input['country'];
            $order_product->product_price = $input['product_price'];
            $order_product->vat_price = $input['vat_price'];
            $order_product->shipment_price = $input['shipment_price'];
            $order_product->net = $input['net'];
            $order_product->status_order = $input['status_order'];
            $order_product->product_detail = $input['product_detail'];
            $order_product->created = $input['created'];
            $order_product->updated = $input['updated'];

            $res = $order_product->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_product,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update order_product",
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $res = OrderProduct::find($id)->delete();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully",
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete order_product",
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete order_product, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
}
