<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    //------------------------------------------------------------ login normal / login wait_user
    
    public function login(Request $request)
    {

        $mobile = $request->get('username');
        $credentials = [
            'mobile' => $mobile,
            'password' => $request->get('password'),
        ];

        $validator = Validator::make($credentials, [
            'mobile' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ]);

        //check missed something in body
        if ($validator->fails()) {
            return response([
                'status' => 'error missed credentails',
                'code' => 0,
                'message' => $validator->errors(),
            ], 200);
        }

        //get status of authorized
        $isAuhtorized = auth()->attempt($validator->validated());

        //case: credential normal working ok
        if( $isAuhtorized ) {
            return $this->loginSuccess($isAuhtorized);
        }
        
        //get user by phone 
        $user_by_phone = Users::where( "mobile", "=", $mobile )->first();
        if( $user_by_phone == null ) {
            return response([
                'status' => 'error_phone_not_found',
                'code' => 0,
                "new_account" => 1,
                'message' => "This Phone Number not have account",
            ], 200);
        }
        
        //check user is not verfied before
        /**
         when user not verfied, means he is waiting for first login
         */
        if( $user_by_phone->email_verified_at != null ) {
            return response([
                'status' => 'error_verified_before',
                'code' => 0,
                'message' => "Unauthorized",
            ], 200);
        }

        // case: "waiting user"
        return $this->loginToWait($request);
    }

    //------------------------------------------------------------------ login to wait user

    private function loginToWait(Request $request )  {
        /**
         this default password created by admin when create "wait" user
         */
        $passWait = env("Project_wait_password");
        $credentials_wait = [
            'mobile' => $request->get('username'),
            'password' => $passWait,  //set default wait password
        ];
        $validator_wait = Validator::make($credentials_wait, [
            'mobile' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ]);

        $authorizeWaitUser = auth()->attempt($validator_wait->validated());

        //check the password is not that password created by the admin
        if( $authorizeWaitUser == false ) {
            return response([
                'status' => 'error_not_same_password_admin_create',
                'code' => 0,
                'message' => "Unauthorized",
            ], 200);
        }

        //Now prevent make it again, allow only oneTimeLogin
        $this->preventLoginToThisAccountByWaitAgain($request  );

        
        //now return response of login wiat user
        $responsJson =  $this->loginSuccess($authorizeWaitUser);

       // echo "response of login wiat user";

        return $responsJson;
    }


    private function updateVerifedEmailAt(){
                //get user model
                $user_security = auth()->user();
                $user_model = Users::where("id", "=", $user_security->id )->first();
                $user_model->email_verified_at = date('Y_m_d__H_i_s');
                $user_model->update();
    }

    /**
     the wait user allow to login just one time,
    Admin how create user without verify sms phone, make password "wait" password , 
    then after user verify sms the password change for this user
     */
    private function preventLoginToThisAccountByWaitAgain(Request $request ) {
        
        //get user login
        $user = auth()->user();

        //check already verfied
        if(  $user->email_verified_at != null ) {
            echo "user already verified";
            return;
        }

        //change password
        $passWait = env("Project_wait_password");
        $newPassword_firebaseUID = $request->password;
        $this->changePassword( $passWait, $newPassword_firebaseUID );

        //verfied date set now
        /**
         the verfied data is the value to check is "oneTimeLogin" before
         */
        $this->updateVerifedEmailAt();
    }


    public function changePassword( $oldPassword, $newPassword ) {
        $old_bcrypt = bcrypt($oldPassword);
        $new_bcrypt = bcrypt($newPassword);
        
        //get current login user password
        $current_bcrypt = auth()->user()->password; 
        //dd($current_bcrypt);

        /** ----- here admin only how change
            //check not same old password
        if( $current_bcrypt != $old_bcrypt ) {
            echo"not allowed to change, the old passowd not same";
            return false;            
        }    
         */


        //get user model
        $user_security = auth()->user();
        $user_model = Users::where("id", "=", $user_security->id )->first();

        //change user password
        $user_model->password = "".$new_bcrypt;
        $user_model->update();
      //  echo"change password done new: ".$new_bcrypt;


        return true;

    }

    //------------------------------------------------------------------------- register

    /**
     open from two way:
       1- student user register by himself
       2- admin create new user
     */
    public function register(Request $request)
    {

    
        $userOrJson = $this->createNewUser($request);

        //check return "user table" or "json response"
        $isJson = $userOrJson instanceof Users  == false;
        if( $isJson)   {
            return $userOrJson;
        }
        $user = $userOrJson; //it's type of table 'users"

        //update verfiy
        $user->email_verified_at = date('Y_m_d__H_i_s');
        $user->save();

        //token
        $request->username = $request->mobile; //why set key "username" ?  because JWT check for this parameter name
        $validator = $this->getValidator( $request);
        $token = auth()->attempt($validator->validated());

        return response([
            'status' => 'success',
            'code' => 1,
            'token' => $token,
            'message' => "Users successfully registered",
            'user_already_found' => false,
            //  "email_verified_at" => $email_verified_at,
            'data' => $user,
        ], 201);
    }
 
    public function getValidator(Request $request ) {
       // dd($request->all());
        $validator = Validator::make($request->all(), [

            //'name' => 'required|string',
            //'email' => 'required|string',
            // 'email_verified_at' => 'date_format:Y-m-d H:i:s',
            'password' => 'required|string|confirmed|min:6',
            'remember_token' => 'string',
            'fid' => 'required|string',
            'country' => 'required|string',
            'mobile' => 'required|string|between:2,100',
            //'created_at' => 'date_format:Y-m-d H:i:s',
            //'updated_at' => 'date_format:Y-m-d H:i:s',
        ]);

        return $validator;
    }

    public function createNewUser(Request $request) {
        // $request->email_verified_at = $email_verified_at;

        $validator = $this->getValidator( $request);

        //validate requuest
        if ($validator->fails()) {
           // return response()->json($validator->errors()->toJson(), 200);
           return response([
            'status' => 'failed',
            'code' => 0,
            'message' => "".response()->json($validator->errors()->toJson(), 200),
        ], 200);
        }

        //check unique mobile
        $objectFoundBefore = Users::where('mobile', '=', $request->mobile)->first();
        if ($objectFoundBefore != null) {
            return response([
                'status' => 'failed',
                'code' => 0,
                'user_already_found' => true,
                'message' => "Users already found",
            ], 200);
            return;
        }

        //appened array
        $arrayToCreate = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
                'role' => "student",
                //   "email_verified_at" => $email_verified_at
            ]
        );

        //create now
        $user = Users::create($arrayToCreate);
        return $user;
    }

    //----------------------------------------------------------------
    
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->loginSuccess(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        try {
            return response([
                'status' => 'success',
                'code' => 1,
                'message' => "Token Generated",
                'data' => auth()->user(),
            ], 200);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get user profile. {$exception->getMessage()}",
            ], 200);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loginSuccess($token)
    {

        $provider = Provider::where( "user_id", "=",  auth()->user()->id )->first();

        return response([
            'status' => 'success',
            'code' => 1,
            'message' => "Token Generated",
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                //   'expires_in' => auth()->factory()->getTTL(),
                'user' => auth()->user(),
                "provider" => $provider
            ],
        ], 200);
    }

}
