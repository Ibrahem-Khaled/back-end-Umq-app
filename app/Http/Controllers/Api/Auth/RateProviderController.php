<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\RateProvider;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\Language\LanguageTools;

class RateProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
        
            $rate_provider = RateProvider::paginate($request->paginator, ['*'], 'page', $request->page);
            if ($rate_provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_provider
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get rate_provider, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }

    public  function calculateRate($id ) {
        //get target we need to calculate
        $array = RateProvider::where('provider_id', '=', $id)->get();

        //get count
        $count = $array->count();

        //check not found
        if( $count == 0 ){
            /**
             * to avoid crash divided by zero
             */
            return 0;
        }

        //appened all sums of rates
        $totalValueSum = 0.0;
        foreach ($array as $i) {
            $totalValueSum += $i->value;
        }

        //divided by how many user make rate
        return  $totalValueSum/$count  ;
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
            
     

            //get previous
            $previous = RateProvider::
            where('provider_id', '=', $request->provider_id)
            ->where('user_id', '=', $request->user_id)
            ->first();

            //check not allowed change rate
            if ($previous != null) {
                return response([
                    'status' => LanguageTools::choose( $request, 'Not Allowed To Change Your Previous Rate', "لا يمكنك تعديل تقيمك السابق"),
                    'code' => 0,
                    "rate_before" => true
                ], 200);
            }

 
            $rate_provider = RateProvider::create($request->all());
            $rate_provider->save();
 
            return response([
                'status' =>  LanguageTools::msg_success( $request ) ,
                'code' => 1,
                "rate_before" => false,
                'data' => $rate_provider
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' =>  LanguageTools::msg_failed( $request ) ,
                'code' => 0,
                'message' => "Failed to store rate_provider, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','value','provider_id','user_id','created_at'];
            $rate_provider = RateProvider::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($rate_provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_provider
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get rate_provider, please try again. {$exception->getMessage()}"
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
            $rate_provider = RateProvider::where('id', '=', $id)->first();
            if ($rate_provider) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_provider
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get rate_provider data, please try again. {$exception->getMessage()}"
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

            $rate_provider = RateProvider::find($id);

           $rate_provider->value = $input['value'];$rate_provider->provider_id = $input['provider_id'];$rate_provider->user_id = $input['user_id'];$rate_provider->created_at = $input['created_at'];

            $res = $rate_provider->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_provider
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update rate_provider"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update rate_provider, please try again. {$exception->getMessage()}"
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
            $res = RateProvider::find($id)->delete();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully"
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete rate_provider"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete rate_provider, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }
}

