<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\SubscribeUser;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Boolean;
use App\Http\Controllers\Api\Abdallah\FcmAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;

class SubscribeUserController extends Controller
{

        //---------------------------------------------------------------------- check action allowed with subscribe package


        public  function is_student_allow_to_chat_with_this_provider(int $user_id_search): bool
        {
 
            //check setting prevented
            if(SettingAdminController::is_subscribe_prevented() ) {
                return true;
            }

            //check null exception
            $conUser = new UsersController();
            $target_user = $conUser->getSingleObject($user_id_search);
           // dump($target_user);
            if( $target_user == null ) return true;

            //case : student or admin not to validate
            $isProvider  = $target_user->role == "provider";
            $is_admin_or_student = $isProvider == false ;
            //dump($isProvider);
            if( $is_admin_or_student) return true;

            //case: provider
            $subscribe_package = $this->get_model_current_user_subscibe($user_id_search);
            //dump($subscribe_package);
            if($subscribe_package == null ) { return false; }

            //case : type subscribition not allowing chat
            $not_allow_chat = $subscribe_package->package_allowed_chat == 0;
            //dump($not_allow_chat);
            if( $not_allow_chat ) {
                return false;
            }

            return true;

        }

        

        public  function is_provider_allow_to_chat_with_student(int $user_id_search): bool
        {

            //check setting prevented
            if(SettingAdminController::is_subscribe_prevented() ) {
                return true;
            }

            //case student allowed
            $isIamStudent = SecurityAbdallah::isUserStudent();
            if( $isIamStudent) { return true; }
 
            //check null exception
            $conUser = new UsersController();
            $target_user = $conUser->getSingleObject($user_id_search);
            if( $target_user == null ) return true;

            //case : chat with admin allowed
            $is_target_admin  = $target_user->role == "admin";
            if( $is_target_admin) return true;

            //get provider user id
            $provider_user_id = SecurityAbdallah::getUserId();

            //case: provider
            $subscribe_package = $this->get_model_current_user_subscibe($provider_user_id);
            //dump($subscribe_package);
            if($subscribe_package == null ) { return false; }

            //case : type subscribition not allowing chat
            $not_allow_chat = $subscribe_package->package_allowed_chat == 0;
            //dump($not_allow_chat);
            if( $not_allow_chat ) {
                return false;
            }

            return true;

        }

    //--------------------------------------------------------------------------------------------- current subsribe

    /// check any user id send in request is subscribe or not
    public function current_user_subscibe(Request $request)
    {
        try {

            $subscribe_package = $this->get_model_current_user_subscibe($request->user_id);

            //paginate($request->paginator, ['*'], 'page', $request->page)
            if ($subscribe_package) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_package
                ], 200);
            } else {
                return response([
                    'status' => 'not_found',
                    'code' => 0,
                    'message' => "No subscribation avaliable at this time"
                ], 202);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get subscribe_user, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    
    public function get_model_current_user_subscibe(int $user_id_search) {
        
        if( $this->avaliable_day_before_expire_subscribtion( $user_id_search ) >= 1 ) {
            $latest_subsribe_by_this_user = SubscribeUser::
            orderBy('id' )
            ->where("user_id", "=", $user_id_search )
            ->first();
            return $latest_subsribe_by_this_user;
        } else {
            return null;
        }
    }


    public function is_allow_to_subscibe_or_upgrade(Request $request, Int $new_period)  {
        $current_subscribe = $this->get_model_current_user_subscibe($request->user_id);

        //case not current subscribe
        if($current_subscribe == null ) {
            return true;
        } 
        
        if( $this->avaliable_day_before_expire_subscribtion( $request->user_id ) < $new_period ) {
            return true;
        } 

        return false;
    }



    public function avaliable_day_before_expire_subscribtion(int $user_id_search){
        //compare the expired is too long than the new subscribe period
        $now = Carbon::now(); 
        $expire_date = $this->current_subsribe_end_at($user_id_search);
        $difDaysPostive = $now->diffInDays( $expire_date);

        //put negative when first date is smallest
        if($now > $expire_date){
          $negative =  -$difDaysPostive;
         // dump("avaliable_day_before_expire_subscribtion() - nagative case: ".$negative);
          return $negative;
       } else {
       // dump("avaliable_day_before_expire_subscribtion() - postive case: ".$difDaysPostive);
        return $difDaysPostive;
       }
    }


    public function current_subsribe_end_at( int $user_id_search) {
       // $user_id = SecurityAbdallah::getUserId();
        $latest_subsribe_by_this_user = SubscribeUser::
            orderBy('id' )
            ->where("user_id", "=", $user_id_search )
            ->first();


        //case first time subscibe
        if($latest_subsribe_by_this_user == null ){
            return null;
        }

      //get info about start/end time
      $created = $latest_subsribe_by_this_user->created;
      $start_subscribe = Carbon::parse($created);
      $end_subscibe = $start_subscribe->addDays($latest_subsribe_by_this_user->package_period);
    //   dd($end_subscibe);
    return $end_subscibe;
    }


    //--------------------------------------------------------------------------------- create 

   
    public function store_by_visa(Request $request)
    {

       // admin only allowed
        $isOwner = SecurityAbdallah::isUserOwner( $request->user_id);
       if( $isOwner == false ) {
                            return response([
                                'status' => 'you_are_user_your_userid',
                                'code' => 0,
                                "subscribe_to_free_plan_before" => false,
                                'message' => "Not Allowed Action",
                            ], 200); 
      }
                

        //check is not allowed days
        if( $this->is_allow_to_subscibe_or_upgrade($request, $request->package_period) == false ) {
            $avaliable_days = $this->avaliable_day_before_expire_subscribtion( $request->user_id);    
            $msg = "Not Allowed To Change Subscribtion, Due To Your Current Subsribtion Will Expire After "
                     .$avaliable_days." Days. You Must Choose Greater Than Period Days Of New Package";

            return response([
                'status' => 'change_subsribe_not_allowed',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' =>  $msg ,
            ], 200); 
        }
                        
                        
        try {
            $subscribe_user = SubscribeUser::create($request->all());
            $subscribe_user->save();

            //fcm: push
            $conFCM = new FcmAbdallah();
            $resFCM_customer = $conFCM->subscribe_to_customer($request, $subscribe_user);
            $resFCM_admin = $conFCM->subscribe_to_admin($request, $subscribe_user);
            
            //set push fcm status
            $fcmSuccess = FcmAbdallah::$serverKeyEmptyMessage != $resFCM_customer;

            return response([
                'status' => 'success',
                'code' => 1,
                "subscribe_to_free_plan_before" => false,
                'resFCM_customer' => $resFCM_customer,
                'resFCM_admin' => $resFCM_admin,
                'data' => $subscribe_user
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' => "Failed to store subscribe_user, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }


    public function store_by_admin(Request $request)
    {

       // admin only allowed
        $isAdmin = SecurityAbdallah::isUserAdmin();
       if( $isAdmin == false ) {
                            return response([
                                'status' => 'admin_user_needed',
                                'code' => 0,
                                "subscribe_to_free_plan_before" => false,
                                'message' => "Not Allowed Action",
                            ], 200); 
      }
                

        //check is not allowed
        if( $this->is_allow_to_subscibe_or_upgrade($request, $request->package_period) == false ) {
            $avaliable_days = $this->avaliable_day_before_expire_subscribtion( $request->user_id );    
            $msg = "Not Allowed To Change Subscribtion, Due To Your Current Subsribtion Will Expire After "
                     .$avaliable_days." Days. You Must Choose Greater Than Period Days Of New Package";

            return response([
                'status' => 'change_subsribe_not_allowed',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' =>  $msg ,
            ], 200); 
        }

                        
        try {
            $subscribe_user = SubscribeUser::create($request->all());
            $subscribe_user->save();

            //fcm: push
            $conFCM = new FcmAbdallah();
            $resFCM_customer = $conFCM->subscribe_to_customer($request, $subscribe_user);
            $resFCM_admin = $conFCM->subscribe_to_admin($request, $subscribe_user);
            
            //set push fcm status
            $fcmSuccess = FcmAbdallah::$serverKeyEmptyMessage != $resFCM_customer;

            return response([
                'status' => 'success',
                'code' => 1,
                "subscribe_to_free_plan_before" => false,
                'resFCM_customer' => $resFCM_customer,
                'resFCM_admin' => $resFCM_admin,
                'data' => $subscribe_user
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' => "Failed to store subscribe_user, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    
    public function store_at_free_package(Request $request)
    {

       // admin only allowed
       $isOwner = SecurityAbdallah::isUserOwner( $request->user_id);
       if( $isOwner == false ) {
        return response([
            'status' => 'you_are_user_your_userid',
            'code' => 0,
            "subscribe_to_free_plan_before" => false,
            'message' => "Not Allowed Action",
        ], 200); 
        }

                

        //check is not allowed
        if( $this->is_allow_to_subscibe_or_upgrade($request, $request->package_period) == false ) {
            $avaliable_days = $this->avaliable_day_before_expire_subscribtion( $request->user_id );    
            $msg = "Not Allowed To Change Subscribtion, Due To Your Current Subsribtion Will Expire After "
                     .$avaliable_days." Days. You Must Choose Greater Than Period Days Of New Package";

            return response([
                'status' => 'change_subsribe_not_allowed',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' =>  $msg ,
            ], 200); 
        }

        //make type free
        $request->merge(['payment_method' => "free"]);
               
        
        // check user subscribe to free package before
        if( $this->is_user_subscribe_to_this_free_package_before($request) ) {
            return response([
                'status' => 'change_subsribe_not_allowed',
                'code' => 0,
                "subscribe_to_free_plan_before" => true,
                'message' => 'Your Have Subscribe To This Free Package Before',
            ], 200); 
        }

                        
        try {
            $subscribe_user = SubscribeUser::create($request->all());
            $subscribe_user->save();

            //fcm: push
            $conFCM = new FcmAbdallah();
            $resFCM_customer = $conFCM->subscribe_to_customer($request, $subscribe_user);
            $resFCM_admin = $conFCM->subscribe_to_admin($request, $subscribe_user);
            
            //set push fcm status
            $fcmSuccess = FcmAbdallah::$serverKeyEmptyMessage != $resFCM_customer;

            return response([
                'status' => 'success',
                'code' => 1,
                "subscribe_to_free_plan_before" => false,
                'resFCM_customer' => $resFCM_customer,
                'resFCM_admin' => $resFCM_admin,
                'data' => $subscribe_user
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                "subscribe_to_free_plan_before" => false,
                'message' => "Failed to store subscribe_user, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }


    public function is_user_subscribe_to_this_free_package_before(Request $request ) {
        $freeSubscribe = SubscribeUser::
        where("payment_method", "=", "free" )
        ->where("user_id", "=", $request->user_id )
        ->where("package_id", "=", $request->package_id )
        ->first();

        if( $freeSubscribe ){
            return true;
        } else {
            return false;
        }
    }

    //---------------------------------------------------------------------------------------- curd

    public function index(Request $request)
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
        
            $subscribe_user = SubscribeUser::
            orderBy('id', 'desc')
            ->paginate($request->paginator, ['*'], 'page', $request->page);
            
            if ($subscribe_user) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_user
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
                'message' => "Failed to get subscribe_user, please try again. {$exception->getMessage()}"
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
                            $searchQuery = trim($search);
                            $requestData = ['id','user_id','user_name','user_phone','user_image','package_id','package_name','package_period','package_	allowed_product_numers','package_allowed_chat','price','start_date','expire_date','by_admin','payment_method','payment_transaction_id','created','updated'];
                            $subscribe_user = SubscribeUser::where(function ($q) use ($requestData, $searchQuery) {
                                foreach ($requestData as $field)
                                    $q->orWhere($field, 'like', "%{$searchQuery}%");
                            })->paginate($request->paginator, ['*'], 'page', $request->page);
                            if ($subscribe_user) {
                                return response([
                                    'status' => 'success',
                                    'code' => 1,
                                    'data' => $subscribe_user
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
                                'message' => "Failed to get subscribe_user, please try again. {$exception->getMessage()}"
                            ], 500);
                        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
     //check admin or user owner allowed
     $isOwner = SecurityAbdallah::isUserOwner($request->user_id);
     if ($isOwner == false ) {
        return response([
            'status' => 'owner_user_needed',
            'code' => 0,
            'message' => "Not Allowed Action",
        ], 200); 
     }

        try {
            $subscribe_user = SubscribeUser::where('id', '=', $id)->first();
            if ($subscribe_user) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_user
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
                'message' => "Failed to get subscribe_user data, please try again. {$exception->getMessage()}"
            ], 500);
        }
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
 
     //check admin or user owner allowed
     $isOwner = SecurityAbdallah::isUserOwner($request->user_id);
     if ($isOwner == false ) {
        return response([
            'status' => 'owner_user_needed',
            'code' => 0,
            'message' => "Not Allowed Action",
        ], 200); 
     }
                    
        try {
            $input = $request->all();

            $subscribe_user = SubscribeUser::find($id);



           $subscribe_user->user_id = $input['user_id'];
           $subscribe_user->user_name = $input['user_name'];
           $subscribe_user->user_phone = $input['user_phone'];
           $subscribe_user->user_image = $input['user_image'];
           $subscribe_user->package_id = $input['package_id'];
           $subscribe_user->package_name = $input['package_name'];
		   $subscribe_user->package_period = $input['package_period'];
		   $subscribe_user->package_allowed_product_numers = $input['package_allowed_product_numers'];
		   $subscribe_user->package_allowed_chat = $input['package_allowed_chat'];
           $subscribe_user->start_date = $input['start_date'];
           $subscribe_user->expire_date = $input['expire_date'];
           $subscribe_user->price = $input['price'];
           $subscribe_user->by_admin = $input['by_admin'];
           $subscribe_user->payment_method = $input['payment_method'];
           $subscribe_user->payment_transaction_id = $input['payment_transaction_id'];
           $subscribe_user->created = $input['created'];
           $subscribe_user->updated = $input['updated'];

            $res = $subscribe_user->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $subscribe_user
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update subscribe_user"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update subscribe_user, please try again. {$exception->getMessage()}"
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
            $res = SubscribeUser::find($id)->delete();
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
                    'data' => "Failed to delete subscribe_user"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete subscribe_user, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
}

