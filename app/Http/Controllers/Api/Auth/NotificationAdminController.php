<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\FcmAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\NotificationAdmin;
use Illuminate\Http\Request;

class NotificationAdminController extends Controller
{

    //--------------------------------------------------------------- normal..

    public function getNormalUserListNotification(Request $request)
    {
        try {

            $user_id = SecurityAbdallah::getUserId();
            $role_key = "role_".SecurityAbdallah::getUserRole() ;

            $notification_admin = NotificationAdmin::
                where("topic", "=", "id_" . $user_id)
                ->orWhere("topic", "=", "all")
                ->orWhere("topic", "=", $role_key)
                ->orderBy( "id", "desc")
                ->limit($request->limit)
                ->get();

                          //get target user id
            foreach ($notification_admin as $m) {
                $this->resourceObject( $m );
            }

            if ($notification_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'role_key' => $role_key,
                    'data' => $notification_admin,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get notification_admin, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }


    //--------------------------------------------------------------- admin

    public function getCreatedByAdminListNotification(Request $request)
    {
        try {
        // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if ($isAdmin == false) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not Allowed Action",
            ], 200);
        }

            $notification_admin = NotificationAdmin::
                where("added_by", "=", "admin" )

                ->orWhere("topic", "=", "all")
                ->orderBy( "id", "desc")
                ->limit($request->limit)
                ->paginate($request->paginator, ['*'], 'page', $request->page);

                          //get target user id
            foreach ($notification_admin as $m) {
                $this->resourceObject( $m );
            }

            if ($notification_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $notification_admin,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get notification_admin, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    //-------------------------------------------------------------- get list  

    public function index(Request $request)
    {
        try {

                    // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if ($isAdmin == false) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not Allowed Action",
            ], 200);
        }

        
            $notification_admin = NotificationAdmin::
                orderBy("id", "desc")
                ->paginate($request->paginator, ['*'], 'page', $request->page);

            //get target user id
            foreach ($notification_admin as $m) {
                $this->resourceObject( $m );
            }

            if ($notification_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $notification_admin,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get notification_admin, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    //--------------------------------------------------------------------- resource

    public function resourceObject(NotificationAdmin $m) {

        //check null
        if ( $m == null ) return;

        //check is containt keyword "all"
        $isAll = str_contains( $m->topic, "all");
        if( $isAll ) return;

       //check is containt keyword "role_admin"
       $isRoleAdmin = str_contains( $m->topic, "role_admin");
       if( $isRoleAdmin ) return;

                
        //target
        $target_user_id = $this->getUserIdFromTopic( $m->topic );

        //get target user id
        $conUser = new UsersController();
        $user_target = $conUser->getSingleObject( $target_user_id );
        $m["user_target"] = $user_target;
    }


    /**
     * return the user id from "id_xx" example "id_5" return 5
     */
    public function getUserIdFromTopic( $topic ) {
        
        $patterns = array();
        $patterns[0] = '/id_/';

        $replacements = array();
        $replacements[0] = '';

        $result =  preg_replace($patterns, $replacements,  $topic);
        return $result;
 
    } 

    //---------------------------------------------------------------------- create new

    public function store(Request $request)
    {

        // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
        if ($isAdmin == false) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not Allowed Action",
            ], 200);
        }

        try {
            $notification_admin = NotificationAdmin::create($request->all());
            $notification_admin->save();

            //fcm: push
            $conFCM = new FcmAbdallah();
            $resFCM = $conFCM->admin($request, $notification_admin);

            //fcm: get status
            $fcmSuccess = FcmAbdallah::$serverKeyEmptyMessage != $resFCM;
            $notification_admin->fcm_status = $fcmSuccess;

            //fcm: update in db
            $notification_admin->fcm_message_id = $resFCM;
            $notification_admin->update();

            //get target
            $this->resourceObject( $notification_admin );


            return response([
                'status' => 'success',
                'code' => 1,
                "resFCM" => $resFCM,
                "fcm_status" => $fcmSuccess,
                'data' => $notification_admin 
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store notification_admin, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }

    //---------------------------------------------------------------------------- order status
    
    public function store_after_push($title, $message, $topic, $fcmResult )
    {
  
                    //check it's success push
        $fcm_status = FcmAbdallah::$serverKeyEmptyMessage != $fcmResult;
  
                    
        try {
            $notification_admin = NotificationAdmin::create([
                'topic' => $topic,
                'title' => $title,
                'message' => $message,
                "fcm_message_id" => $fcmResult,
                "fcm_status" => $fcm_status,
                "added_by" => "automatic"
            ]);

            $notification_admin->save();
 
            //get target
            $this->resourceObject( $notification_admin );

            return 1;
        } catch (\Exception$exception) {
            return 0;
        }
    }

    //--------------------------------------------------------------------------------------- serach

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($search, Request $request)
    {
        try {
            $searchQuery = trim($search);
            $requestData = ['id', 'topic_android', 'topic_ios', 'title', 'message', 'fcm_status', 'created', 'updated'];
            $notification_admin = NotificationAdmin::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field) {
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                }

            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($notification_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $notification_admin,
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get notification_admin, please try again. {$exception->getMessage()}",
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
            $notification_admin = NotificationAdmin::where('id', '=', $id)->first();
            if ($notification_admin) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $notification_admin,
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found",
                ], 404);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get notification_admin data, please try again. {$exception->getMessage()}",
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
        try {
            $input = $request->all();

            $notification_admin = NotificationAdmin::find($id);

            $notification_admin->topic_android = $input['topic_android'];
            $notification_admin->topic_ios = $input['topic_ios'];
            $notification_admin->title = $input['title'];
            $notification_admin->message = $input['message'];
            $notification_admin->fcm_status = $input['fcm_status'];
            $notification_admin->created = $input['created'];
            $notification_admin->updated = $input['updated'];

            $res = $notification_admin->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $notification_admin,
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update notification_admin",
            ], 500);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update notification_admin, please try again. {$exception->getMessage()}",
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
        try {
            $res = NotificationAdmin::find($id)->delete();
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
                    'data' => "Failed to delete notification_admin",
                ], 500);
            }
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete notification_admin, please try again. {$exception->getMessage()}",
            ], 500);
        }
    }
}
