<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\City;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;

class CityController extends Controller
{



    //-----------------------------------------------------  get single model

    public function getSingleObject(  $target)
    { //:  Users
        try {
            $user = City::where('id', '=', $target)->first();

            if ($user) {
                return $user;
            } else {
                return null; // new Users();
            }

        } catch (\Exception$exception) {
            return null; // new Users();
        }

    }


    //----------------------------------------------------  update data

    /**
    call from v2
     */
    public function updateData(Request $request )
    {
 
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
        $m = City::where('id', '=', $request->id)->first();

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
    
    //----------------------------------------------------  hidden

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
        $m = City::where('id', '=', $request->target)->first();

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


    //--------------------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
        
            $statusOptional = $request->status;
            if( $statusOptional == null ) {
                $statusOptional = true;
            }
        

            $city = City::
            where( "status","=", $statusOptional )
            ->paginate($request->paginator, ['*'], 'page', $request->page);

            if ($city) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $city
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
                'message' => "Failed to get city, please try again. {$exception->getMessage()}"
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

             $city = City::create($request->all());
            $city->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $city
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store city, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','name_ar','name_en','lat','lng','status','created_at','updated_at'];
            $city = City::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($city) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $city
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
                'message' => "Failed to get city, please try again. {$exception->getMessage()}"
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
            $city = City::where('id', '=', $id)->first();
            if ($city) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $city
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
                'message' => "Failed to get city data, please try again. {$exception->getMessage()}"
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

            $city = City::find($id);

           $city->id = $input['id'];$city->name_ar = $input['name_ar'];$city->name_en = $input['name_en'];$city->lat = $input['lat'];$city->lng = $input['lng'];$city->status = $input['status'];$city->created_at = $input['created_at'];$city->updated_at = $input['updated_at'];

            $res = $city->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $city
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update city"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update city, please try again. {$exception->getMessage()}"
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
            $res = City::find($id)->delete();
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
                    'data' => "Failed to delete city"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete city, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }
}

