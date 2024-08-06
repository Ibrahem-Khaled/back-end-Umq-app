<?php

namespace App\Http\Controllers\Api\Abdallah;

use App\Models\Users;
use App\Traits\ResponseForm;
use Illuminate\Http\Request;
 
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
 
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
 
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Api\Auth\ProviderController;

class SecurityAbdallah extends Controller {
 
    //----------------------------------------------------------------------------- check hacker data

    public static function checkHtmlContainHackingCommand($data) : bool{
        if($data == null ) return false;
        if(str_contains( $data, "<script") ) return true;

        /**
             may good code like
             <img src="data:image/jpg;base64

              if(str_contains( $data, "src=") ){
            
            return true;
        } 
             */
       
        // if(str_contains( $data, "type=//"module//"") ) return true;
        if(str_contains( $data, "<?php") ) return true;
        //good health
        return false;
    }

    //------------------------------------------------------------------------------------

    public static function   getUserFromToken() : Users {
      
        //check user have token in header
        $token = JWTAuth::getToken();
        if(!$token){
            /**
             * case not login
             */
          return new Users();
        }

      
        try {
            $user = JWTAuth::parseToken()->authenticate();

            //check token is not expired
            if( $user == null ) {
                return new Users();
            }

            return  $user;
        } catch(\Exception$e){
            return new Users();
        }
     
    }

    
    public static function   isTokenExpire(Request $request ) : bool {
      

        try{
            $user_id = SecurityAbdallah::getUserId();
            
           // case: user Guest not login yet
           $tokenWithBearer =   $request->header('lang');
            if( $tokenWithBearer == null ) {
                 // dump("isTokenExpire() - tokenWithBearer: $tokenWithBearer");
                // dump("isTokenExpire() - tokenWithBearer == null - result is false");
                return false;
            }

           //case token expire
            if( $user_id == null || $user_id == 0 ) {
                return true;
            }

            /**
             * good health
             */
            return false;
        } catch (\Exception$exception) {
            return false;
        }
    }

    public static function   getUserId() : int {
      
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user->id;
        } catch (\Exception$exception) {
            return 0;
            /**
             response([
                'status' => 'error',
                'code' => 0,
                'message' => "User Not parse error {$exception->getMessage()}",
            ], 200);
             */
        }
    }

    
    public static function   getUserRole() : String {
      
        try{
            $user = JWTAuth::parseToken()->authenticate();
            return $user->role;
        } catch (\Exception$exception) {
            return "";
    
        }
    }


    /**
     how to use
             //check admin only
        if( SecurityAbdallah::isUserAdmin() == false ) {
            return response([
                'status' => 'error not allowed',
                'code' => 0,
                'message' => "Not allowed action",
            ], 200);
        }
     */
    public static function  isUserAdmin() : bool {
       

        $user = JWTAuth::parseToken()->authenticate();
       // dd($user);
  

        if ($user == null)  {
            return false;
        }
  
        if ($user->block == true )  {
            return false;
        }
        if ($user->hidden == true )  {
            return false;
        }
       
        //only case
        if ($user->role == "admin" )  {
            //echo "yes admin - role: ".$user->role;
        
            return true;
        }
       
        //default
        return false;
    }

    
    public static function  isUserStudent() : int {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user == null)  {
            return 0;
        }
  
        if ($user->block == true )  {
            return 0;
        }
        if ($user->hidden == true )  {
            return 0;
        }
       
        //only case
        if ($user->role == "student" )  {
            //echo "print role: ".$user->role;
            return 1;
        }
       
        //default
        return 0;
    }



    public static function  isUserProvider() : int {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user == null)  {
            return 0;
        }
  
        if ($user->block == true )  {
            return 0;
        }
        if ($user->hidden == true )  {
            return 0;
        }
       
        //only case
        if ($user->role == "provider" )  {
            //echo "print role: ".$user->role;
            return 1;
        }
       
        //default
        return 0;
    }


    public static function  isUserOwner( $ownerId ) : int {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user == null)  {
            return 0;
        }
  
        if ($user->block == true )  {
            return 0;
        }
        if ($user->hidden == true )  {
            return 0;
        }
       
        //case admin
        if ($user->role == "admin" )  {
            return 1;
        }
       
        //case "ownerId" same "userId"
        if ($user->id  ==  $ownerId  )  {
            return 1;
        }

        //default
        return 0;
    }


    
    public static function  isProviderOwner( $provider_id ) : int {

        $user = JWTAuth::parseToken()->authenticate();
        if ($user == null)  {
            return 0;
        }
     
     
        //case admin
        if ($user->role == "admin" )  {
            return 1;
        }

        // get user id of provider
        $contProvider = new ProviderController();
        $provider =  $contProvider->getProviderObjectOfThisUserId( $user->id );
        if( $provider == null ) {
            return 0;
        }

        $ownerId = $provider->user_id;

        if ($user->block == true )  {
            return 0;
        }
        if ($user->hidden == true )  {
            return 0;
        }
       
   
       
        //case "ownerId" same "userId"
        if ($user->id  ==  $ownerId  )  {
            return 1;
        }

        //default
        return 0;
    }


}