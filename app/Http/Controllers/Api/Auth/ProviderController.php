<?php

namespace App\Http\Controllers\Api\Auth;

// use App\Http\Controllers\api\Abdallah\JsonResourceAbdallah;
use App\Http\Controllers\Api\Abdallah\JsonResourceAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{

    //------------------------------------------------------------------   filter

    public function filter(Request $request)
    {
        $query = Provider::with(['users', 'city', 'organization'])
            ->where('id', ">", 0)
            ->where('block', "=", false)
            ->where('hidden', "=", false);
          

        //city_id ( optional)
        if( $request->city_id != null ) {
            $query->where('city_id', "=", $request->city_id);
        }
            
        //org_id ( optional)
        if( $request->organization_id != null ) {
            $query->where('org_id', "=", $request->organization_id);
        }

        //get array
        $dataProvider  = $query->paginate($request->paginator);
            
        //get rate provider
        foreach ($dataProvider as $providerIndex) {
            $rateCon = new RateProviderController();
            $providerIndex["rate"] = $rateCon->calculateRate($providerIndex->id);
        }

        //get favorite
        foreach ($dataProvider as $m) {
            $favCon = new FavProviderController();
            $value = $favCon->getFavorite($m->id);
            $m["favorite"] = $value;
            $m["user_id"] = SecurityAbdallah::getUserId();
        }

        return $dataProvider;
    }


    public static function getAllStatusAvaliable(Request $request)
    {
        $dataProvider = Provider::with(['users', 'city', 'organization'])
            ->where('id', ">", 0)
            ->where('block', "=", false)
            ->where('hidden', "=", false)
            ->paginate($request->paginator);

            

        //get rate provider
        foreach ($dataProvider as $providerIndex) {
            $rateCon = new RateProviderController();
            $providerIndex["rate"] = $rateCon->calculateRate($providerIndex->id);
        }

        //get favorite
        foreach ($dataProvider as $m) {
            $favCon = new FavProviderController();
            $value = $favCon->getFavorite($m->id);
            $m["favorite"] = $value;
            $m["user_id"] = SecurityAbdallah::getUserId();
        }

        // return $dataProvider;

 
        //fix deleted user from database due to apple store need to remove information
        $dataProviderFilter = [];
        foreach ($dataProvider as $m) {
            if( $m->users ) {
                $dataProviderFilter[] = $m;
            }
        } 
        
        $count = Provider::where('id', ">", 0)
        ->where('block', "=", false)
        ->where('hidden', "=", false)
        ->count();

        $total_float = $count /  $request->paginator;
        $total = (int) round( $total_float );
        $current_page = $request->page;

 
        $mokeResponseLaravel =   JsonResourceAbdallah::responseLikeLaravelPaginate($dataProviderFilter, $total, $current_page , $request->paginator );
        return $mokeResponseLaravel;
    }

    //---------------------------------------------------------------------- get single

    
    public function show($id)
    {
        try {
  
            $provider = $this->getSingle($id);

            if ($provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $provider,
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
                'message' => "Failed to get provider data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    
    public function show_provider_by_userid(Request $request)
    {
        try {

            //dump($request);

            $provider = $this->getProviderObjectOfThisUserId($request->user_id);
           // dump($provider);

            if ($provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $provider,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found show_provider_by_userid",
                ], 200);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get provider data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function getSingle($provider_id)
    {
        $provider = Provider::with(['users', 'city', 'organization'])
            ->where('id', '=', $provider_id)
            ->first();

        //rate
        $rateCon = new RateProviderController();
        $rate_value = $rateCon->calculateRate($provider_id);
        $provider["rate"] = $rate_value;

        return $provider;
    }

    
    public function getUserIdOfThisProviderId($provider_id_search)
    {
        $provider = Provider::where('id', '=', $provider_id_search)->first();
            return $provider->user_id;
    }

    
    /// this method under test
    public function getUserObjectOfThisProviderId($provider_id_search)
    {
        $provider = Provider::where('id', '=', $provider_id_search)->first();
        $user_id_of_this_provider =  $provider->user_id;

        $contUser = new UsersController();
        $user = $contUser->getSingleObject($user_id_of_this_provider);

        if($user) {
            return $user;
        } else {
            return null;
        }
    }


    public function getProviderObjectOfThisUserId($user_id)
    {
        $provider = Provider::with(['users', 'city', 'organization'])
        ->where('user_id', '=', $user_id)
            ->first();
 
        return $provider;
    }

    public function getProviderIdOfThisUserId($user_id)
    {
        $provider = Provider::where('user_id', '=', $user_id)
            ->first();
 
        return $provider->id;
    }




    //----------------------------------------------------------------------- get list
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $provider = Provider::with(['users', 'city', 'organization'])
            //  ->where( 'block', "=", false )
                ->where('hidden', "=", false)
                ->paginate($request->paginator);

            if ($provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $provider,
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
                'message' => "Failed to get provider, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
 
    //------------------------------------------------------------------------------------ create 

    public function store(Request $request)
    {
        try {

            //get my userId
            $isOwner = SecurityAbdallah::isUserOwner( $request->user_id );


            //check request edit another user id
            if( $isOwner == false  ) {
                return response([
                    'status' => 'security issue',
                    'code' => 0,
                    'message' => "Not Allowed To Change To Provider" 
                ], 200);
            }

            //query
            $provider = Provider::where('user_id', '=', $request->user_id )->first();
    
            //check already provider
            if( $provider != null ) {
                $provider->whats =  $request->whats; 
                $provider->city_id =$request->city_id;
                $provider->org_id = $request->org_id; 
                $res = $provider->update();
            } else {
             //case new provider 
            $provider = Provider::create($request->all() );
            $provider->save();
    
            }
 

            //update city id 
            $result_update_cityId = UsersController::updateCityId($request->city_id, $request->user_id);
          
          

                        
            //update role type for table "user"
            $result_update_roleType = UsersController::updateRoleToTypeProvider($request->user_id);

   
            
            return response([
                'status' => 'success',
                'code' => 1,
                'result_update_roleType' => $result_update_roleType,
                'result_update_cityId' => $result_update_cityId,
                'data' => $provider,
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store provider, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

  
    //------------------------------------------------------------------------------------ search and filter

    public function search($search, Request $request)
    {
        try {
            $searchQuery = trim($search);
            $requestData = ['id', 'user_id', 'whats', 'city_id', 'org_id', 'created_at', 'updated_at'];
            $provider = Provider::with(['users', 'city', 'organization'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $provider,
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
                'message' => "Failed to get provider, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
 
 //-------------------------------------------------------------------------------------------- update

    public function update(Request $request, $id)
    {

                    //get my userId
                    $isOwner = SecurityAbdallah::isUserOwner( $request->id );


                    //check request edit another user id
                    if( $isOwner == false  ) {
                        return response([
                            'status' => 'security issue',
                            'code' => 0,
                            'message' => "Not Allowed" 
                        ], 200);
                    }

                    
        try {
            $input = $request->all();

            $provider = Provider::find($id);

            // $provider->id = $input['id'];
            $provider->user_id = $input['user_id'];
            $provider->whats = $input['whats'];
            $provider->city_id = $input['city_id'];
            $provider->org_id = $input['org_id'];
            $provider->created_at = $input['created_at'];
            $provider->updated_at = $input['updated_at'];

            $res = $provider->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $provider,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update provider",
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update provider, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

 

}
