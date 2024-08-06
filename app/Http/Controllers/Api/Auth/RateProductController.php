<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\RateProduct;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;

class RateProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
        
            $rate_product = RateProduct::with(['product','users'])->paginate($request->paginator);
            if ($rate_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_product
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
                'message' => "Failed to get rate_product, please try again. {$exception->getMessage()}"
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


    public  function calculateRate($id ) {
        //get target we need to calculate
        $array = RateProduct::where('product_id', '=', $id)->get();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {

            //get previous
            $previous = RateProduct::
            where('product_id', '=', $request->product_id)
            ->where('user_id', '=', $request->user_id)
            ->first();

            //check not allowed change rate
            if ($previous != null) {
                return response([
                    'status' => 'Not allow to change rate',
                    'code' => 0,
                    'message' => "Already rated",
                    "data" => $previous
                ], 200);
            }

            $rate_product = RateProduct::create($request->all());
            $rate_product->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $rate_product
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store rate_product, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','value','product_id','user_id','created_at'];
            $rate_product = RateProduct::with(['product','users'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($rate_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_product
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
                'message' => "Failed to get rate_product, please try again. {$exception->getMessage()}"
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
            $rate_product = RateProduct::with(['product','users'])->where('id', '=', $id)->first();
            if ($rate_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_product
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
                'message' => "Failed to get rate_product data, please try again. {$exception->getMessage()}"
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

            $rate_product = RateProduct::find($id);

           $rate_product->value = $input['value'];$rate_product->product_id = $input['product_id'];$rate_product->user_id = $input['user_id'];$rate_product->created_at = $input['created_at'];

            $res = $rate_product->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $rate_product
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update rate_product"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update rate_product, please try again. {$exception->getMessage()}"
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
            $res = RateProduct::find($id)->delete();
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
                    'data' => "Failed to delete rate_product"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete rate_product, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }
}

