<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\SubscribePackage;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class SubscribePackageController extends Controller
{


    //------------------------------------------------------------------------------------------------ curd

    public function get_with_paginate(Request $request)
    {
        try {
        
            $subscribe_package = SubscribePackage::
            orderBy('price' )
            ->where("hidden", "=", 0 )
            ->paginate($request->paginator, ['*'], 'page', $request->page);
 
            if ($subscribe_package) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get subscribe_package, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all(Request $request)
    {
        try {
        
            $subscribe_package = SubscribePackage::
            orderBy('price' )
            ->where("hidden", "=", 0 )
            ->get();

            //paginate($request->paginator, ['*'], 'page', $request->page)
            if ($subscribe_package) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get subscribe_package, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

                // admin only allowed
                $isAdmin = SecurityAbdallah::isUserAdmin();
                if( $isAdmin == false ) {
                    return response([
                        'status' => 'admin_user_needed',
                        'code' => 0,
                        'message' => "Not Allowed Action",
                    ], 200); 
                }

                
        try {
            $subscribe_package = SubscribePackage::create($request->all());
            $subscribe_package->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $subscribe_package
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store subscribe_package, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','name_en','name_ar','description_en','description_ar','price','period','allowed_product_numers','allowed_chat','status','created','updated'];
            $subscribe_package = SubscribePackage::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($subscribe_package) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get subscribe_package, please try again. {$exception->getMessage()}"
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
            $subscribe_package = SubscribePackage::where('id', '=', $id)->first();
            if ($subscribe_package) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get subscribe_package data, please try again. {$exception->getMessage()}"
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

                // admin only allowed
                $isAdmin = SecurityAbdallah::isUserAdmin();
                if( $isAdmin == false ) {
                    return response([
                        'status' => 'admin_user_needed',
                        'code' => 0,
                        'message' => "Not Allowed Action",
                    ], 200); 
                }

                
        try {
            $input = $request->all();

            $subscribe_package = SubscribePackage::find($id);
        

            if( $request->name_en) {
                $subscribe_package->name_en = $input['name_en'];
            }
            if( $request->name_ar) {
                $subscribe_package->name_ar = $input['name_ar'];
            }
            if( $request->description_en) {
                $subscribe_package->description_en = $input['description_en'];
            }
            if( $request->description_ar) {
                $subscribe_package->description_ar = $input['description_ar'];
            }
            if( $request->price) {
                $subscribe_package->price = $input['price'];
            }
            if( $request->period) {
                $subscribe_package->period = $input['period'];
            }
            if( $request->allowed_product_numers != null ) {
                $subscribe_package->allowed_product_numers = $input['allowed_product_numers'];
            }
            // if( $request->allowed_chat != null ) {
                $subscribe_package->allowed_chat = $input['allowed_chat'];
            // }
            if( $request->hidden != null ) {
                $subscribe_package->hidden = $input['hidden'];
            }
            if( $request->color != null ) {
                $subscribe_package->color = $input['color'];
            }
            if( $request->updated) {
                $subscribe_package->updated = $input['updated'];
            }
 
    
 

            $res = $subscribe_package->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update subscribe_package"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update subscribe_package, please try again. {$exception->getMessage()}"
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

                // admin only allowed
                $isAdmin = SecurityAbdallah::isUserAdmin();
                if( $isAdmin == false ) {
                    return response([
                        'status' => 'admin_user_needed',
                        'code' => 0,
                        'message' => "Not Allowed Action",
                    ], 200); 
                }

                
        try {
            $res = SubscribePackage::find($id)->delete();
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
                    'data' => "Failed to delete subscribe_package"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete subscribe_package, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
}

