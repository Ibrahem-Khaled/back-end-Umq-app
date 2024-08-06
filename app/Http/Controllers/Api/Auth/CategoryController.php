<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        try {

            $query = Category::
                where("hidden", "=", "0");

            //query optional status   
            if ( $request->status != ""   ) {
                $query->where("status", "=", $request->status);
            }

            $category = $query->paginate($request->paginator, ['*'], 'page', $request->page);

            if ($category) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $category,
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
                'message' => "Failed to get category, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $category = Category::create($request->all());
            $category->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $category,
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store category, please try again. {$exception->getMessage()}",
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
            $requestData = ['id', 'name_en', 'name_ar', 'description', 'hidden', 'status', 'image', 'created_at', 'updated_at'];
            $category = Category::
                where("hidden", "=", "0")
                ->where(function ($q) use ($requestData, $searchQuery) {
                    foreach ($requestData as $field) {
                        $q->orWhere($field, 'like', "%{$searchQuery}%");
                    }

                })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($category) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $category,
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
                'message' => "Failed to get category, please try again. {$exception->getMessage()}",
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
            $category = Category::where('id', '=', $id)->first();
            if ($category) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $category,
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
                'message' => "Failed to get category data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {

        // //security admin only
        // $isAdmin = SecurityAbdallah::isUserAdmin();
        // if ($isAdmin == false) {
        //     return response([
        //         'status' => 'error',
        //         'code' => 0,
        //         'message' => "Admin Only Allowe To Edit The Category",
        //     ], 200);
        // }

        try {
            $input = $request->all();

            $category = Category::find($id);

            if ($request->name_en) {
                $category->name_en = $input['name_en'];
            }

            if ($request->name_ar) {
                $category->name_ar = $input['name_ar'];
            }

            if ($request->description) {
                $category->description = $input['description'];
            }

            if ($request->hidden) {
                $category->hidden = $input['hidden'];
            }

            if ($request->status == 0 || $request->status == 1) {
                $category->status = $input['status'];
            }

            if ($request->image) {
                $category->image = $input['image'];
            }
            // dd($request->status);

            //$category->created_at = $input['created_at'];$category->updated_at = $input['updated_at'];

            $res = $category->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $category,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update category",
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update category, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

}
