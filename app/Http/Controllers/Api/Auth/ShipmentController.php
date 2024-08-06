<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class ShipmentController extends Controller
{

    //---------------------------------------------------------------------------- v2

    public function updateData(Request $request )  {

        // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if( $isAdmin == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not Allowed Action",
            ], 200); 
        }

        //query
        $m = Shipment::where('id', '=', $request->id)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this object id",
            ], 200);
        }

        //update
        if ($request->name_ar != null) {
            $m->name_ar = $request->name_ar;
        }
        if ($request->name_en != null) {
            $m->name_en = $request->name_en;
        }
 
        //update
        $res = $m->update();

        //when user create return "json"
        if ($res) {
            return response([
                'status' => 'success update',
                'code' => 1,
                'data' => $m,
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update",
            ], 200);
        }

    }

    
    public function hidden(Request $request) {

        //   dd($request);
   
           //check admin only
           if( SecurityAbdallah::isUserAdmin() == false ) {
               return response([
                   'status' => 'error not allowed',
                   'code' => 0,
                   'message' => "Not allowed action",
               ], 200);
           }
   
           //query
           $m = Shipment::where('id', '=', $request->target)

           ->first();
   
           //check not found
           if ($m == null) {
               return response([
                   'status' => 'error not found',
                   'code' => 0,
                   'message' => "Not found this target",
               ], 200);
           }
   
           //update
           $m->status = $request->value;
   
           //update
           $res = $m->update();
           if ($res) {
               return response([
                   'status' => 'success update',
                   'code' => 1,
                   'data' => $m,
               ], 200);
           } else {
               return response([
                   'status' => 'error',
                   'code' => 0,
                   'data' => "Failed to update",
               ], 200);
           }
       }
   

    //---------------------------------------------------------------------------- v1
     
    public function index(Request $request)
    {
        try {
        
            $shipment = Shipment::
            where( "status","=", "1" )
            ->paginate($request->paginator, ['*'], 'page', $request->page);

            if ($shipment) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $shipment
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
                'message' => "Failed to get shipment, please try again. {$exception->getMessage()}"
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
            $shipment = Shipment::create($request->all());
            $shipment->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $shipment
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store shipment, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','name_ar','name_en','status'];
            $shipment = Shipment::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($shipment) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $shipment
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
                'message' => "Failed to get shipment, please try again. {$exception->getMessage()}"
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
            $shipment = Shipment::where('id', '=', $id)->first();
            if ($shipment) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $shipment
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
                'message' => "Failed to get shipment data, please try again. {$exception->getMessage()}"
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $res = Shipment::find($id)->delete();
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
                    'data' => "Failed to delete shipment"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete shipment, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
}

