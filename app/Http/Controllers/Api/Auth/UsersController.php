<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

     
    //-------------------------------------------------------------------------- get single

    public function get_single($user_id)
    { 
        
        return   Users::
        with(['city'])
        ->where('id', '=', $user_id)->first();
    }
 
 /**
     this currently used to get profile of user himself
     */
    public function show($id) {
        try {
            $users = Users::
                with(['city'])
                ->where('id', '=', $id)->first();
            if ($users) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $users,
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
                'message' => "Failed to get users data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
    //-----------------------------------------------------  get single model

    public function getSingleObject(  $target)
    { //:  Users
        try {
            $user = Users::where('id', '=', $target)->first();

            if ($user) {
                return $user;
            } else {
                return null; // new Users();
            }

        } catch (\Exception$exception) {
            return null; // new Users();
        }

    }

    /**
     * the public data of users
     */
    public function getUserIdBasicDataForChat(int $target)
    { //:  Users
        try {
            $user = Users::where('id', '=', $target)->first();

            if ($user) {
                return $user;
            } else {
                return null; // new Users();
            }

        } catch (\Exception$exception) {
            return null; // new Users();
        }

    }

    //---------------------------------------------------- get all users

    public function index(Request $request)
    {
        try {
                // admin only allowed
                $isAdmin = SecurityAbdallah::isUserAdmin();
                if( $isAdmin == false ) {
                    return response([
                        'status' => 'admin_user_needed',
                        'code' => 0,
                        'message' => "Not Allowed Action",
                    ], 200); 
                }
                
            $users = $this->queryFilter($request);

            if ($users) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $users,
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
                'message' => "Failed to get users, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    private function queryFilter(Request $request)
    {

        $roleOptional = $request->role;
        if ($roleOptional != null) {
            $query = Users::
                where("hidden", "=", 0)
                // ->where("role", "!=", "admin")
               // ->where("role", "=", $request->role)
                ->with(['city'])
                ->orderBy('id', 'desc');

                ///case need to show provider, return also admin, because admin have products at market
                if($request->role == "provider" ) {
                    $query ->where("role", "=", $request->role)
                           ->orWhere("role", "=", "admin");
                } else {
                    $query->where("role", "=", $request->role);
                }
                

                 $users = $query->paginate($request->paginator, ['*'], 'page', $request->page);
            return $users;
        } else {
            $users = Users::
                where("hidden", "=", 0)
                // ->where("role", "!=", "admin")
                ->with(['city'])
                ->orderBy('id', 'desc')
                ->paginate($request->paginator, ['*'], 'page', $request->page);
            return $users;
        }
    }

    //----------------------------------------------------  block

    public function block(Request $request)
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
                        

        //query
        $m = Users::
            with(['city'])
            ->where('id', '=', $request->target)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this target",
            ], 200);
        }

        //update
        $m->block = $request->value;

        //update table "provider" for query
        $mProvider = Provider::where("user_id", "=", $m->id)->first();

        if ($mProvider != null) {
            $mProvider->block = $request->value;
            $mProvider->update();
        }

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

    //----------------------------------------------------  hidden or delete 

    /**
     * apple store need this api
     */
    public function delete(Request $request) {
                        // admin only allowed
        $isOwner = SecurityAbdallah::isUserOwner( $request->user_id);
        if( $isOwner == false ) {
                            return response([
                                'status' => 'you not owner',
                                'code' => 0,
                                'message' => "Not Allowed Action",
                            ], 200); 
       }
                        

        //query
        $m = Users::
            with(['city'])
            ->where('id', '=', $request->user_id)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this target",
            ], 200);
        }

      
        $res = $m->delete();

        if ($res) {
            return response([
                'status' => 'success delete',
                'code' => 1
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update",
            ], 200);
        }
    } 

    public function hidden(Request $request)
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
                        

        //query
        $m = Users::
            with(['city'])
            ->where('id', '=', $request->target)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this target",
            ], 200);
        }

        //update table "user"
        $m->hidden = $request->value;

        //update table "provider" for query
        $mProvider = Provider::where("user_id", "=", $m->id)->first();

        if ($mProvider != null) {
            $mProvider->hidden = $request->value;
            $mProvider->update();
        }

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

    //----------------------------------------------------------- generata pass

    public function passwordGenerate(Request $request)
    {

        //check role is admin
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if ($isAdmin == false) {
            return "not allowed";
        }

        //
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 200);
        }

        $pass = bcrypt($request->password);
        return $pass;

    }

    //---------------------------------------------------- create wait user by admin

    /**
    admin create this wait user
     */
    public function createWaitUser(Request $request)
    {

        try {

            //security admin only
            $isAdmin = SecurityAbdallah::isUserAdmin();
            if ($isAdmin == false) {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "Admin only allowed",
                ], 200);
            }

            //edit the request
            $passWait = env("Project_wait_password");
            $request->merge(['password' => $passWait]);
            $request->merge(['password_confirmation' => $passWait]);
            $request->merge(['fid' => "wait"]);

            //step 1 : create new record
            $conAuth = new AuthController();
            $userOrJson = $conAuth->createNewUser($request);

            //check return "user table" or "json response"
            $isJson = $userOrJson instanceof Users == false;
            if ($isJson) {
                return $userOrJson;
            }
            $user = $userOrJson; //it's type of table 'users"

            //step 2 : update data
            $userIdTarget = $user->id;
            $status_update = $this->updateData($request, $userIdTarget);

            return response([
                'status' => 'success',
                'code' => 1,
                'status_update' => $status_update,
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store users, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //-----------------------------------------------------------------search

    public function search_text(Request $request)
    {

        // dd( $request->search );

        try {
            $searchQuery = trim($request->search);

            $requestData = ['id', 'name', 'email', 'email_verified_at', 'country', 'mobile', 'role', 'block', 'hidden', 'photo', 'created_at', 'updated_at'];

            $query = null;
            if( $request->role == null  ) {
                $query = Users::
                with(['city'])
                ->where("hidden", "=", 0) 
                ->where(function ($q) use ($requestData, $searchQuery) {
                    foreach ($requestData as $field) {
                        $q->orWhere($field, 'like', "%{$searchQuery}%");
                    }

                });
            } else if( $request->role ==  "provider" ) {
                $query = Users::
                with(['city'])
                ->where("hidden", "=", 0) 
                ->where("role", "!=",   "student" )
                ->orderBy('id', 'desc')
                ->where(function ($q) use ($requestData, $searchQuery) {
                    foreach ($requestData as $field) {
                        $q->orWhere($field, 'like', "%{$searchQuery}%");
                    }

                });
            } else if( $request->role ) {
                $query = Users::
                with(['city'])
                ->where("hidden", "=", 0) 
                ->where("role", "=",  $request->role )
                ->orderBy('id', 'desc')
                ->where(function ($q) use ($requestData, $searchQuery) {
                    foreach ($requestData as $field) {
                        $q->orWhere($field, 'like', "%{$searchQuery}%");
                    }

                });
            }
 

                $users = $query->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($users) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $users,
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
                'message' => "Failed to get users, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //------------------------------------------------------------------------------------------ update

    /**
    call from v2
     */
    public function updateData(Request $request, $userByAdmin = 0)
    {

        //case : user him self update his profile
        $user_id = SecurityAbdallah::getUserId();

        //+++++++++++++++++++ admin only allowed

        $isAdmin = SecurityAbdallah::isUserAdmin();

        if ($isAdmin) {

            //case: open from another method make validateion
            if ($userByAdmin != 0) {
                $user_id = $userByAdmin;
            }

            //case : user_id passed in request
            if ($request->target_user_id != null) {
                $user_id = $request->target_user_id;
            }
        }

        //++++++++++++++++++++++++ query

        //query
        $m = Users::
            with(['city'])
            ->where('id', '=', $user_id)->first();
        // dd($user_id);

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this user id " . $user_id,
            ], 200);
        }

        //update
        if ($request->name != null) {
            $m->name = $request->name;
        }
        if ($request->email != null) {
            $m->email = $request->email;
        }
        if ($request->photo != null) {
            $m->photo = $request->photo;
        }
        if ($request->city_id != null) {
            $m->city_id = $request->city_id;
        }

        //update
        $res = $m->update();

        //when admin update return "boolean"
        if ($userByAdmin != 0) {
            return $res;
        }

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
 
    
    /**
    v1 not used now
     */
    public function update(Request $request, $id)
    {

        // dd( $request->all() );

        try {
            $input = $request->all();

            $users = Users::
                with(['city'])
                ->find($id);

            $users->name = $input['name'];
            $users->email = $input['email'];
            $users->photo = $input['photo'];
            //  $users->email_verified_at = $input['email_verified_at'];
            // $users->password = $input['password'];$users->remember_token = $input['remember_token'];$users->fid = $input['fid'];$users->country = $input['country'];$users->mobile = $input['mobile'];$users->role = $input['role'];$users->block = $input['block'];$users->hidden = $input['hidden'];$users->created_at = $input['created_at'];$users->updated_at = $input['updated_at'];

            $res = $users->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $users,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update users",
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update users, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }


    public function updatePhoto(Request $request, $photoNew): bool
    {

        $user_id = SecurityAbdallah::getUserId();

        //query
        $m = Users::where('id', '=', $user_id)->first();

        //check not found
        if ($m == null) {
            return response([
                'status' => 'error not found',
                'code' => 0,
                'message' => "Not found this user id " . $user_id,
            ], 200);
        }

        //update
        $m->photo = $photoNew;

        //update
        $res = $m->update();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

 
    public static function updateRoleToTypeProvider($id): bool
    {
        $users = Users::find($id);
        $users->role = 'provider';
        $res = $users->update();
        return $res;
    }
 
    public static function updateCityId($city_id, $user_id): bool
    {
        // $user_id = SecurityAbdallah::getUserId();

        $users = Users::find($user_id);
        $users->city_id = $city_id;
        $res = $users->update();
        return $res;
    }


}
