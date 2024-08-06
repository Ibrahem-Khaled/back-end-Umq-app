<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Organization;
use Illuminate\Http\Request;
use Facade\Ignition\Tabs\Tab;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class OrganizationController extends Controller
{

    

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
        $m = Organization::where('id', '=', $request->id)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this object id",
            ], 200);
        }

        //update
        if ($request->name != null) {
            $m->name = $request->name;
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
           $m = Organization::where('id', '=', $request->target)->first();
   
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
        
            $organization = Organization::
            where( "status","=", $statusOptional )
            ->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($organization) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $organization
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
                'message' => "Failed to get organization, please try again. {$exception->getMessage()}"
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
            $organization = Organization::create($request->all());
            $organization->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $organization
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store organization, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','name','status','created_at','updated_at'];
            $organization = Organization::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($organization) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $organization
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
                'message' => "Failed to get organization, please try again. {$exception->getMessage()}"
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
            $organization = Organization::where('id', '=', $id)->first();
            if ($organization) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $organization
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
                'message' => "Failed to get organization data, please try again. {$exception->getMessage()}"
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

            $organization = Organization::find($id);

           $organization->name = $input['name'];$organization->status = $input['status'];$organization->created_at = $input['created_at'];$organization->updated_at = $input['updated_at'];

            $res = $organization->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $organization
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update organization"
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update organization, please try again. {$exception->getMessage()}"
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
            $res = Organization::find($id)->delete();
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
                    'data' => "Failed to delete organization"
                ], 200);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete organization, please try again. {$exception->getMessage()}"
            ], 200);
        }
    }
}

