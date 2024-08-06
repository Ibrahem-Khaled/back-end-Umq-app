<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\FavProduct;
use Illuminate\Http\Request;

class FavProductController extends Controller
{

    /**
     * is favorite
     */
    public function getFavorite($product_id): int
    {

        //get userid from token
        $user_id = SecurityAbdallah::getUserId();

        //check user id not found
        if ($user_id == null || $user_id == 0) {
            return 0;
        }

        //get object
        $fav_product = FavProduct::
            where('user_id', '=', $user_id)
            ->where('product_id', '=', $product_id)
            ->first();

        // dd($fav_product );

        //check no object found
        if ($fav_product == null) {
            return 0;
        }

        //return status
        return $fav_product->favorite;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            //get userid from token
            $user_id = SecurityAbdallah::getUserId();

            //check user id not found
            if ($user_id == null || $user_id == 0) {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "Guest Not ALlowed To Get Favorite",
                ], 200);
            }

            $fav_product = FavProduct::
                with(['users'])
                ->where('user_id', '=', $user_id)
                ->where("favorite", "=", 1)
                ->get();
            //->paginate($request->paginator);
            // dd( $fav_product);

            //get model product
            foreach ($fav_product as $m) {
                $contProduct = new ProductController();
                $product = $contProduct->getModelById($m->product_id);
                $m["product"] = $product;
            }

            //
            if ($fav_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_product,
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
                'message' => "Failed to get fav_product, please try again. {$exception->getMessage()}",
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
            //get userid from token
            $user_id = SecurityAbdallah::getUserId();
            // dd($user_id);

            //get previous
            $previous = FavProduct::
                where('product_id', '=', $request->product_id)
                ->where('user_id', '=', $user_id)
                ->first();

            //   dd($previous);

            //check update values of previous record
            if ($previous != null) {
                return $this->update($request, $previous->id);
            }

            //edit request
            $input = $request->all();
            $input['user_id'] = $user_id;

            //save new
            $fav_product = FavProduct::create($input);
            $fav_product->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $fav_product,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store fav_product, please try again. {$exception->getMessage()}",
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
            $requestData = ['id', 'user_id', 'product_id', 'favorite'];
            $fav_product = FavProduct::with(['users'])->where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($fav_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_product,
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
                'message' => "Failed to get fav_product, please try again. {$exception->getMessage()}",
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
            $fav_product = FavProduct::with(['users'])->where('id', '=', $id)->first();
            if ($fav_product) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $fav_product,
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
                'message' => "Failed to get fav_product data, please try again. {$exception->getMessage()}",
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

            $fav_product = FavProduct::find($id);

            $fav_product->user_id = SecurityAbdallah::getUserId(); // $input['user_id'];
            $fav_product->product_id = $input['product_id'];
            $fav_product->favorite = $input['favorite'];

            $res = $fav_product->update();
            if ($res) {
                return response([
                    'status' => 'success update',
                    'code' => 1,
                    //   'user_id_token' => SecurityAbdallah::getUserId(),
                    'data' => $fav_product,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update fav_product",
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update fav_product, please try again. {$exception->getMessage()}",
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
            $res = FavProduct::find($id)->delete();
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
                    'data' => "Failed to delete fav_product",
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete fav_product, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
