<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth\ProductController;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class OrderVendorController extends Controller
{

    //  -------------------------------------------------------------------------------- get products

    public function getOrderProductDetail(  $orderProductId ) {

        //get vendor array items
        $venderArray = OrderVendor::where( "order_product_id", "=", $orderProductId)->get();


        return $this->resourceArray( $venderArray );
    }

    //-------------------------------------------------------------------------------- resources

    public function resourceArray( $array ) {

        //loop to edit
        foreach ($array as $m) {
            $this->resourceObject( $m );
        }

        //after edit
        return $array;
    }


    public function resourceObject(OrderVendor $m ) {
            /**
                         {
                "id": 15,
                "order_product_id": 33,
                "provider_id": 7,
                "user_id": 16,
                "product_id": 1,
                "product_price": "20.00",
                "product_qty": 1,
                "provider_status": "new",
                "created": 1656417435,
                "updated": 1656417435
            },
             */
            //get product
            $conProduct = new ProductController();
            $m["product"] = $conProduct->getModelById( $m->product_id );

            //get provider
            $conProvider = new ProviderController();
            $m[ "provider"] = $conProvider->getSingle( $m->provider_id );
     }


    //------------------------------------------------------------------------------- insert order

    //insertNewOrder
    public function insertNewOrder(Request $request, $orderProduct)
    {
        //get cart data
        $contCart = new CartProductController();
        $cartArray = $contCart->getCartArrayForSpecificProvider($request->user_id, $request->provider_id);

        //check empty cart
        if ($cartArray == null) {
            return 0.0;
        }
        if ($cartArray->count() == 0) {
            return 0.0;
        }
        
     
        //loop
        foreach ($cartArray as $mCart) {
            $this->insertCart( $request, $mCart , $orderProduct);
        }
    }

    public function insertCart(Request $request, $mCart, $orderProduct ) {
        $contProduct = new ProductController();
        $mProduct = $contProduct->getModelById($mCart->product_id);
       
        OrderVendor::create([
            'order_product_id' => $orderProduct->id,
            'provider_id' =>$mProduct->provider_id,
            'user_id' => $request->user_id,
            'product_id' => $mCart->product_id,
            'product_price' => $mProduct->price ,
            'product_qty' => $mCart->counter,
            'provider_status' => "new"
        ]);
    }

    //------------------------------------------------------------------------- v1

    public function index(Request $request)
    {
        try {

            $order_vendor = OrderVendor::paginate($request->paginator, ['*'], 'page', $request->page);
            if ($order_vendor) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_vendor,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_vendor, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $order_vendor = OrderVendor::create($request->all());
            $order_vendor->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $order_vendor,
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store order_vendor, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($search, Request $request)
    {
        try {
            $searchQuery = trim($search);
            $requestData = ['id', 'order_product_id', 'provider_id', 'user_id', 'product_id', 'product_price', 'product_qty', 'provider_status', 'created', 'updated'];
            $order_vendor = OrderVendor::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($order_vendor) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_vendor,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_vendor, please try again. {$exception->getMessage()}",
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
            $order_vendor = OrderVendor::where('id', '=', $id)->first();
            if ($order_vendor) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_vendor,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get order_vendor data, please try again. {$exception->getMessage()}",
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

            $order_vendor = OrderVendor::
            orderBy( "id", "desc")
            ->find($id);

            $order_vendor->order_product_id = $input['order_product_id'];
            $order_vendor->provider_id = $input['provider_id'];
            $order_vendor->user_id = $input['user_id'];
            $order_vendor->product_id = $input['product_id'];
            $order_vendor->product_price = $input['product_price'];
            $order_vendor->product_qty = $input['product_qty'];
            $order_vendor->provider_status = $input['provider_status'];
            $order_vendor->created = $input['created'];
            $order_vendor->updated = $input['updated'];

            $res = $order_vendor->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $order_vendor,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update order_vendor",
            ], 500);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update order_vendor, please try again. {$exception->getMessage()}",
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
            $res = OrderVendor::find($id)->delete();
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
                    'data' => "Failed to delete order_vendor",
                ], 500);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete order_vendor, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
}
