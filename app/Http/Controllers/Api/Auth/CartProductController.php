<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\Language\LanguageTools;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use Illuminate\Http\Request;

class CartProductController extends Controller
{

    //----------------------------------------------------------------------------- other

    //clear all cart for this user
    public function clearMyCartForSpecificProvidier(Request $request)
    {
        //check owner
        $isOwner = SecurityAbdallah::isUserOwner($request->user_id);
        if ($isOwner == 0) {
            return response([
                'status' => 'failed - user_id not allowed to clear cart',
                'code' => 0,
            ], 200);
        }

        //delete
        $res = CartProduct::
            with(['users'])
            ->where('user_id', '=', $request->user_id)
            ->where('provider_id', '=', $request->provider_id)
            ->delete();
        return $res;
    }

    //--------------------------------------------------------------------------- calculate total price

    /**
     * total price for all products for all providers
     */
    public function getTotalPriceProducts(int $user_id): float
    {
        //get cart
        $contCart = new CartProductController();
        $cartArray = $contCart->getCartArray($user_id);
        if ($cartArray == null) {
            return 0.0;
        }
        if ($cartArray->count() == 0) {
            return 0.0;
        }

        //info
        $result = 0.0;
        $contProduct = new ProductController();

        //loop
        foreach ($cartArray as $mCart) {
            $mProduct = $contProduct->getModelById($mCart->product_id);
            $priceSingle = $mCart->counter * $mProduct->price; //quantity * single price
            $result += $priceSingle;
            // echo $result ;
        }

        return $result;
    }

    public function getCartArray(int $user_id)
    {

        // //check owner
        // $isOwner = SecurityAbdallah::isUserOwner($user_id);
        // if ($isOwner == 0) {
        //     return null;
        // }

        //get current cart for this product
        $cart = CartProduct::
            where('user_id', '=', $user_id)
        //    ->where('provider_id', '=', $request->provider_id)
            ->get();

        return $cart;
    }

    public function getTotalPriceProductsForSpecificProvider(int $user_id, int $provider_id): float
    {
        //get cart
        $contCart = new CartProductController();
        $cartArray = $contCart->getCartArrayForSpecificProvider($user_id, $provider_id);
        if ($cartArray == null) {
            return 0.0;
        }
        if ($cartArray->count() == 0) {
            return 0.0;
        }

        //info
        $result = 0.0;
        $contProduct = new ProductController();

        //loop
        foreach ($cartArray as $mCart) {
            $mProduct = $contProduct->getModelById($mCart->product_id);
            $priceSingle = $mCart->counter * $mProduct->price; //quantity * single price
            $result += $priceSingle;
            // echo $result ;
        }

        return $result;
    }

    public function getCartArrayForSpecificProvider(int $user_id, int $provider_id)
    {
        $cart = CartProduct::
            where('user_id', '=', $user_id)
            ->where('provider_id', '=', $provider_id)
            ->get();

        return $cart;
    }

    //------------------------------------------------------------------------------- cancel

    public function cancel(Request $request)
    {
        //get user
        $user = SecurityAbdallah::getUserFromToken();

        //get current cart for this product
        $cart_previous = CartProduct::
            where('user_id', '=', $user->id)
            ->where('product_id', '=', $request->product_id)
            ->first();

        //update
        /**
         * why not delete the row instead of zero counter?
        To Know when the user cancel his product from cart, help the sales
         */
        $cart_previous->counter = 0; //zero means user cancel the product

        //save
        $res = $cart_previous->update();
        return response([
            'status' => 'success cancel',
            'code' => 1,
            "result" => $res,
            'data' => $cart_previous,
        ], 200);

    }

    //------------------------------------------------------------------------------ increment

    public function increment(Request $request)
    {

        //check provider id
        if ($request->provider_id == null) {
            return response([
                'status' => 'failed - provider_id missed',
                'code' => 0,
            ], 200);
        }

        //get user
        $user = SecurityAbdallah::getUserFromToken();

        //get current cart for this product
        $cart_previous = CartProduct::
            where('user_id', '=', $user->id)
            ->where('product_id', '=', $request->product_id)
            ->where('provider_id', '=', $request->provider_id)
            ->first();

        //case first time add this product
        if ($cart_previous == null) {
            return $this->firstTimeCreateCart($request, $user->id);

            //case incement previous
        } else {

            return $this->incrementPreviousCart($cart_previous);
        }

    }

    public function incrementPreviousCart(CartProduct $cart_previous)
    {

        //calculate new counter
        $previousCounter = $cart_previous->counter;
        $newCounter = $previousCounter + 1;

        //update
        $cart_previous->counter = $newCounter;
        $cart_previous->save();

        return response([
            'status' => 'success change counter',
            'code' => 1,
            'new_counter' => $newCounter,
            'data' => $cart_previous,
        ], 200);
    }

    public function firstTimeCreateCart(Request $request, int $user_id)
    {

        //now create
        try {
            $cart_update = CartProduct::create([
                "user_id" => $user_id,
                "product_id" => $request->product_id,
                "provider_id" => $request->provider_id,
                "counter" => 1,
            ]);
            $cart_update->save();

            return response([
                'status' => 'success create new item at Cart',
                'code' => 1,
                'new_counter' => 1,
                'data' => $cart_update,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //------------------------------------------------------------------------------ decrement

    public function decrement(Request $request)
    {

        //get user
        $user = SecurityAbdallah::getUserFromToken();

        //get current cart for this product
        $cart_previous = CartProduct::
            where('user_id', '=', $user->id)
            ->where('product_id', '=', $request->product_id)
            ->first();

        //case first time add this product
        if ($cart_previous == null) {
            return response([
                'status' => 'Decrement for product already not found',
                'code' => 1,
                'new_counter' => 0,
            ], 200);

            //case incement previous
        } else {
            return $this->decrementPreviousCart($cart_previous);
        }

    }

    public function decrementPreviousCart(CartProduct $cart_previous)
    {

        //calculate new counter
        $previousCounter = $cart_previous->counter;
        $newCounter = $previousCounter - 1;

        //case it's zero counter
        if ($newCounter <= 0) {
            return $this->destroy($cart_previous->id);
        }

        //update
        $cart_previous->counter = $newCounter;
        $cart_previous->save();

        return response([
            'status' => 'success change counter',
            'code' => 1,
            'new_counter' => $newCounter,
            'data' => $cart_previous,
        ], 200);
    }

    //----------------------------------------------------------------------------- get counter

    public function getCounter($product_id): int
    {

        //get user
        $user = SecurityAbdallah::getUserFromToken();

        //get current cart for this product
        $cart_previous = CartProduct::
            where('user_id', '=', $user->id)
            ->where('product_id', '=', $product_id)
            ->first();

        //case not added before
        if ($cart_previous == null) {
            return 0;
        }

        //get current
        $previousCounter = $cart_previous->counter;
        return $previousCounter;
    }

    //--------------------------------------------------------------------------- get all product + badge_counter

    public function badge_counter(Request $request) {

        try {

            $badge_counter = CartProduct::
                with(['users'])
                ->where('user_id', '=', $request->user_id)
                ->where('counter', '!=', 0)
                ->count();

            // //check size
            // $badge_counter = $cart_product->count();
            
            return response([
                'status' => 'success',
                'code' => 1,
                 
                'badge_counter' => $badge_counter,
            ], 200);

        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }


    public function cart_products(Request $request)
    {

        try {

            $cart_product = CartProduct::
                with(['users'])
                ->where('user_id', '=', $request->user_id)
                ->where('counter', '!=', 0)
                ->get();

            //check size
            $size = $cart_product->count();
            if ($size == 0) {
                $msg = LanguageTools::choose($request, 'No Product found at Cart'  , "لا يوجد منتجات في السلة",);
                return response([
                    'status' => $msg,
                    'code' => 1, //must return "1" because process success even there is no data
                ], 200);
            }

            //get model product
            foreach ($cart_product as $m) {
                $contProduct = new ProductController();
                $product = $contProduct->getModelById($m->product_id);
                $m["product"] = $product;
            }

            //get total price
            $totalPrice = $this->getTotalPriceProducts($request->user_id);
            //dd( $totalPrice );
            //get vat and shipment
            //getVatValue

            return response([
                'status' => 'success',
                'code' => 1,
                "totalPrice" => number_format($totalPrice, 2),
                // "vat" => ,
                'data' => $cart_product,
            ], 200);

        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //--------------------------------------------------------------------------- get all provider

    /**
     * return model provider in array
     */
    public function all_provider(Request $request)
    {

        try {

            $providerIds = CartProduct::
                //with(['users'])
                where('user_id', '=', $request->user_id)
                ->where('counter', '!=', 0)
                ->select('provider_id')
                ->distinct()
                ->get();

            //check size
            $size = $providerIds->count();
            if ($size == 0) {
                $msg = LanguageTools::choose($request, 'No Product found at Cart' , "لا يوجد منتجات في السلة" );
                return response([
                    'status' => $msg,
                    'code' => 1, //must return "1" because process success even there is no data
                ], 200);
            }

            /**

             */
            //get model provider
            $arrayProvider = array();
            foreach ($providerIds as $m) {
                $cont = new ProviderController();
                $provider = $cont->getSingle($m->provider_id);
                // dd( $provider);
                $arrayProvider[] = $provider;
            }

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $arrayProvider,
                //'data_provider_ids' => $providerIds
            ], 200);

        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //-------------------------------------------------------------------------------- check for cart

    public function is_cart_have_only_one_provider(Request $request)
    {

        $providerIds = CartProduct::
            //with(['users'])
            where('user_id', '=', $request->user_id)
            ->where('counter', '!=', 0)
            ->select('provider_id')
            ->distinct()
            ->get();

        //check size
        $size = $providerIds->count();

        if ($size == 1) {
            return true;
        } else {
            return false;
        }
    }

    
    public function get_cart_provider_id_first_one(Request $request)
    {

        $cartObj = CartProduct::
            //with(['users'])
            where('user_id', '=', $request->user_id)
            ->where('counter', '!=', 0)
           // ->select('provider_id')
           // ->distinct()
            ->first();

         return $cartObj->provider_id;
    }

    //--------------------------------------------------------------------------------- cart provider get

    public function cart_providers(Request $request)
    {

        try {

            $providerIds = CartProduct::
                //with(['users'])
                where('user_id', '=', $request->user_id)
                ->where('counter', '!=', 0)
                ->select('provider_id')
                ->distinct()
                ->get();

            //check size
            $size = $providerIds->count();
            if ($size == 0) {
                $msg = LanguageTools::choose($request, 'No Product found at Cart',  "لا يوجد منتجات في السلة" ) ;
                return response([
                    'status' => $msg,
                    'code' => 1, //must return "1" because process success even there is no data
                ], 200);
            }

            /**

             */
            //get model provider
            $arrayProvider = array();
            foreach ($providerIds as $m) {
                $cont = new ProviderController();
                $provider = $cont->getSingle($m->provider_id);
                // dd( $provider);
                $arrayProvider[] = $provider;
            }

            $cart_provider = array();
            $index = 0;
            foreach ($arrayProvider as $mProvider) {
                $provider_id = $mProvider->id;
                $single_cart["index"] = $index;
                $single_cart["provider_id"] = $provider_id;
                $single_cart["provider"] = $mProvider;
                $single_cart["cart_product"] = $this->provider_specific_cart_product($request->user_id, $provider_id);
                $cart_provider[] = $single_cart;
                $index = $index + 1;
            }

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $cart_provider,
                //'data_provider_ids' => $providerIds
            ], 200);

        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //--------------------------------------------------------------------------- get cart product

    public function provider_specific_cart(Request $request)
    {
        try {

            //check provider id
            if ($request->provider_id == null) {
                return response([
                    'status' => 'failed - provider_id missed',
                    'code' => 0,
                ], 200);
            }

            $cart_product = $this->provider_specific_cart_product($request->user_id, $request->provider_id);

            //check size
            $size = $cart_product->count();
            if ($size == 0) {
                // $msg = LanguageTools::choose( $request, "لا يوجد منتجات في السلة", 'No Product found at Cart' );
                return response([
                    'status' => 'No Product found at Cart',
                    'code' => 1, //must return "1" because process success even there is no data
                ], 200);
            }

            //get total price
            $totalPrice = $this->getTotalPriceProductsForSpecificProvider($request->user_id, $request->provider_id);
            //dd( $totalPrice );
            //get vat and shipment
            //getVatValue

            return response([
                'status' => 'success',
                'code' => 1,
                "totalPrice" => number_format($totalPrice, 2),
                // "vat" => ,
                'data' => $cart_product,
            ], 200);

        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function provider_specific_cart_product($user_id, $provider_id)
    {

        $cart_product = CartProduct::
            with(['users'])
            ->where('user_id', '=', $user_id)
            ->where('provider_id', '=', $provider_id)
            ->where('counter', '!=', 0)
            ->get();

        //get model product
        foreach ($cart_product as $m) {
            $contProduct = new ProductController();
            $product = $contProduct->getModelById($m->product_id);
            $m["product"] = $product;
        }

        return $cart_product;
    }

    //--------------------------------------------------------------------------- v1

    public function index(Request $request)
    {
        try {

            $cart_product = CartProduct::with(['users'])->paginate($request->paginator);

            if ($cart_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $cart_product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
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
            $cart_product = CartProduct::create($request->all());
            $cart_product->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $cart_product,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store cart_product, please try again. {$exception->getMessage()}",
            ], 200);
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
            $requestData = ['id', 'user_id', 'product_id', 'counter', 'updated_at'];
            $cart_product = CartProduct::with(['users'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($cart_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $cart_product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product, please try again. {$exception->getMessage()}",
            ], 200);
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
            $cart_product = CartProduct::with(['users'])->where('id', '=', $id)->first();
            if ($cart_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $cart_product,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get cart_product data, please try again. {$exception->getMessage()}",
            ], 200);
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

            $cart_product = CartProduct::find($id);

            $cart_product->user_id = $input['user_id'];
            $cart_product->product_id = $input['product_id'];
            $cart_product->counter = $input['counter'];
            $cart_product->updated_at = $input['updated_at'];

            $res = $cart_product->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $cart_product,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update cart_product",
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update cart_product, please try again. {$exception->getMessage()}",
            ], 200);
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
            $res = CartProduct::find($id)->delete();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'new_counter' => 0,
                    'message' => "Deleted successfully",
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete cart_product",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete cart_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
