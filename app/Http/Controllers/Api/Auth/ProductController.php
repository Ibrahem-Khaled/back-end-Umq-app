<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Users;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    //----------------------------------------------------------------------- filter and search and get get list

    
    public function index(Request $request)
    {
        try {

            $product = Product::
            with(['provider', 'category'])
            ->where("hidden", "=", "0")
            ->where("status", "=", "1")
            ->paginate($request->paginator);

            if ($product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }


    public function search_by_word(  Request $request)
    {

        // dump("search_by_word product ".$request);

        try {
            $searchQuery = trim($request->word);
            $requestData = ['id', 'name_ar', 'name_en', 'description_en', 'description_ar', 'price', 'status', 'hidden', 'rent', 'provider_id', 'image', 'created_at', 'updated_at'];
            $product = Product::with(['provider', 'category'])
            ->where("hidden", "=", "0")
            ->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    "searchQuery" => $searchQuery,
                    'data' => $product,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    
    public function filter(Request $request)
    {
        // dd("filter customer ".$request);

        //builder query ( mandatory )
        $query = Product::with(['provider', 'category'])
            ->where('id', ">", 0)
            // ->where('status', "=", $request->status)
            ->where('hidden', "=", "0");

        //builder  query ( optional)
        if ($request->category_id != null) {
            $query->where('category_id', "=", $request->category_id);
        }
        if ($request->rent_type != null) {
            $query->where('rent', "=", $request->rent_type);
        }
        if ($request->status !=  "" ) {
            $query->where('status', "=", $request->status);
        }
        if ($request->provider_id) {
            $query->where('provider_id', "=", $request->provider_id);
        }
        if ($request->word) {
            $searchQuery = trim($request->word);
            $requestData = ['id', 'name_ar', 'name_en', 'description_en', 'description_ar', 'price', 'status', 'hidden', 'rent', 'provider_id', 'image', 'created_at', 'updated_at'];
            $query->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            });
        }

        //get data
        $dataPaginated = $query->paginate($request->paginator);
 
        //resoruces
        foreach ($dataPaginated as $m) {
            $this->resourceTheObject($m);
        }

    

        if ( $request->response_shape_status_and_code) {
            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $dataPaginated,
            ], 200);
        } else {
            return $dataPaginated;
        }

    }

    //-------------------------------------------------------------------------- product for provdier

    /**
     * return array of product entered by this provider
     */
    public function productForProviderId($provider_id, $status = true)
    {
        $array = Product::with(['provider', 'category'])
            ->where("provider_id", "=", $provider_id)
            ->where('status', "=", $status)
            ->where('hidden', "=", false)
            ->get();
 
         foreach ($array as $m) {
            $this->resourceTheObject($m);
        }

        return $array;
    }

    //------------------------------------------------------------------- resource object

    public function resourceTheObject(  $product) {

        //check null
        if ($product == null) {
            return;
        }

        //rate
        $rateCon = new RateProductController();
        $rate_value = $rateCon->calculateRate($product->id);
        $product["rate"] = $rate_value;

        //fav
        $favCon = new FavProductController();
        $value = $favCon->getFavorite($product->id);
        $product["favorite"] = $value;
        $product["user_id"] = SecurityAbdallah::getUserId();

        //cart
        $cartCon = new CartProductController();
        $value = $cartCon->getCounter($product->id);
        $product["cart_counter"] = $value;

        //set data provider
        $this->setToModelProductTheProviderData($product);

    }


    public function setToModelProductTheProviderData(Product $product)
    {
        //user data
        $provider = Provider::where("id", "=", $product->provider_id)->first();
        $user = Users::where("id", "=", $provider->user_id)->first();

        //edit
        $product["provider_mobile"] = $user->mobile;
        $product["provider_country"] = $user->country;
    }

    //----------------------------------------------------------------------------- get single product

    //get detail
    public function show($id)
    {
        try {
            $product = Product::with(['provider', 'category'])->where('id', '=', $id)->first();
         //   dd($product);
 
            //set data provider
            $this->resourceTheObject($product);
        
            if ($product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $product,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get product data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
    

    public function getModelById(int $product_id)
    {
        $product = Product::with(['provider', 'category'])
            ->where("id", "=", $product_id)
            ->first();

        //resrouce
        $this->resourceTheObject($product);

        return $product;
    }

    //---------------------------------------------------------------------------- counter

    public function get_counter_product_for_this_provider($provider_id) : int {

        $product_count = Product::
          where( "provider_id","=",$provider_id)
          ->where("hidden", "=", "0")
        ->count();

        return $product_count;
    }

    //----------------------------------------------------------------------------- create 
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
        // get user id of provider
        $contProvider = new ProviderController();
        $user_id_of_provider_owner =  $contProvider->getUserIdOfThisProviderId( $request->provider_id );

        //get subscribe package 
        $contSubcribe = new SubscribeUserController();
        $subscribe_package = $contSubcribe->get_model_current_user_subscibe($user_id_of_provider_owner);
       // dump( "subscribe_package: ".$subscribe_package);

        //get how many product for this provider in market
        $counter_product_for_this_provider = $this->get_counter_product_for_this_provider($request->provider_id);
 
        //check not have any subscribe
        if(  SettingAdminController::is_subscribe_allowed() && $subscribe_package == null ) {
            return response([
                'status' => 'this provider not subscribe to any package',
                "need_subscribe" => 1,
                'code' => 0,
            ], 200);
        }

        //check subscribe limit product
        if(  SettingAdminController::is_subscribe_allowed()  ) {
            if( $counter_product_for_this_provider >= $subscribe_package->package_allowed_product_numers   ) {
                $msg =  'Subscribe Package Limit Just To Add '. $subscribe_package->package_allowed_product_numers." Item"; 
                return response([
                    'status' => $msg,
                    "need_subscribe_limit" => 1,
                    'code' => 0,
                ], 200);
            }
        }


         //check owner
        $isOwner = SecurityAbdallah::isUserOwner($user_id_of_provider_owner);
        if ($isOwner == 0) {
             return response([
            'status' => 'failed this user not allowed to add product for another user',
            'code' => 0,
             ], 200);
        }

        //create
        $product = Product::create($request->all());
        $product->save();

        return response([
            'status' => 'success',
            'code' => 1,
            "counter_product_for_this_provider" => $counter_product_for_this_provider,
            'data' => $product,
        ], 200);
    }

  
    public function update(Request $request, $id)
    {

                 //check owner
                 $isOwner = SecurityAbdallah::isProviderOwner($request->provider_id);
                 if ($isOwner == 0) {
                      return response([
                     'status' => 'failed this provider not allowed to make this action',
                     'code' => 0,
                      ], 200);
                 }
         
        try {
            $input = $request->all();

            $product = Product::find($id);

            $product->id = $id;

            if($request->name_ar) {
                $product->name_ar = $input['name_ar'];
            }
            if($request->name_en) {
                $product->name_en = $input['name_en'];
            }
            if($request->description_ar) {
                $product->description_ar = $input['description_ar'];
            }
            if($request->description_en) {
                $product->description_en = $input['description_en'];
            }
            if($request->price) {
                $product->price = $input['price'];
            }
            if($request->status != "" ) {
                $product->status = $input['status'];
            }
            if($request->hidden) {
                $product->hidden = $input['hidden'];
            }
            if($request->rent) {
                $product->rent = $input['rent'];
            }
            if($request->provider_id) {
                $product->provider_id = $input['provider_id'];
            }
            if($request->image) {
                $product->image = $input['image'];
            }

            $res = $product->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $product,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update product",
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
 
}
