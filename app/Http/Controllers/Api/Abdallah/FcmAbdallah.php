<?php

namespace App\Http\Controllers\Api\Abdallah;

use App\Models\Users;
use App\Models\ChatMessage;
use App\Jobs\ProcessPodcast;
 
use App\Models\OrderProduct;
use App\Traits\ResponseForm;
 
use Illuminate\Http\Request;
use App\Models\SubscribeUser;
 
use App\Services\UploadService;
use App\Models\NotificationAdmin;
use Illuminate\Http\JsonResponse;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Api\Auth\UsersController;
use App\Http\Controllers\Api\Auth\ProviderController;
use App\Http\Controllers\Api\Abdallah\Language\LanguageTools;
use App\Http\Controllers\Api\Auth\NotificationAdminController;
use App\Models\ContactUs;

/**  -- data of mobile android
 {
 "to" : "/topics/all_android",
 "collapse_key" : "type_a",
 "priority" : "high",

  "data" : {
     "type" : "notify",
      "title": "type (data only)",
     "body" : "What is your problem",
     "msg_database_id" : 26,
    "sender_id" : 16,
    "group_notification" :  160052 
 }

}

----------- data  and notification

{
 "to" : "/topics/ios_id_51",
 "collapse_key" : "type_a",
 "priority" : "high",
 "notification" : {
      "title": "type (both-notification)",
     "body" : "welcome 1" 

 },
  "data" : {
     "type" : "notify",
     "title": "make message received",
     "body" : "What is your problem",
     "msg_database_id" : 37,
    "sender_id" : 52,
    "group_notification" :  160052 
 }
}

 */
class FcmAbdallah extends Controller {

    //----------------------------------------------------- constant


    public static String $serverKeyEmptyMessage = "api key is empty";

    //----------------------------------------------------- type subscribe package  

    public function subscribe_to_customer( Request $request ,  SubscribeUser $subscribe_user ) {
        
        //by lang
        $msg = LanguageTools::choose( $request, "تم الاشتراك في العضوية ".$subscribe_user->name_ar,  "Subscribe Plan ".$subscribe_user->name_en );  

        //get info
        $title = "Subscribe Order ID#".$subscribe_user->id;
        $message  =  $msg;
        $topic = "id_".$subscribe_user->user_id;
  
        //data
        $payload = array( );;
        $payload["type"] = "subscribe";
        $payload["topic"] = $topic;  
        $payload["title"] = $title;
        $payload["body"] = $message;

        //push 
       $fcmResult =  $this->push( $topic, $title, $message, $payload );

        //save in table notification
        $notificationCont = new NotificationAdminController();
        $notificationCont->store_after_push($title, $message, $topic, $fcmResult);

        return $fcmResult;
    }


    public function subscribe_to_admin( Request $request ,  SubscribeUser $subscribe_user ) {
            
        //by lang
        $msg = LanguageTools::choose( $request, "تم الاشتراك في العضوية ".$subscribe_user->name_ar,  "Subscribe Plan ".$subscribe_user->name_en );  

        //get info
        $title = "New User Subscribe";
        $message  =  $msg;
        $topic = "role_admin";
  
        //data
        $payload = array( );;
        $payload["type"] = "order";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

                
        //push 
        $fcmResult =  $this->push( $topic, $title, $message, $payload );

        //save in table notification
        $notificationCont = new NotificationAdminController();
        $notificationCont->store_after_push($title, $message, $topic, $fcmResult);

        return $fcmResult;
    }

    //----------------------------------------------------- type order  

    public function order_to_customer( Request $request ,  OrderProduct $order_product ) {
        
        //by lang
        $msg = LanguageTools::choose( $request, "طلبك قيد التحضير",  "Your Order Under Preapearing");  

        //get info
        $title = "Order #".$order_product->id;
        $message  =  $msg;
        $topic = "id_".$order_product->user_id;
  
        //data
        $payload = array( );;
        $payload["type"] = "order";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

        //push 
       $fcmResult =  $this->push( $topic, $title, $message, $payload );

        //save in table notification
        $notificationCont = new NotificationAdminController();
        $notificationCont->store_after_push($title, $message, $topic, $fcmResult);

        return $fcmResult;
    }

    
    public function order_to_provider( Request $request ,  OrderProduct $order_product ) {
        
        $conProvider = new ProviderController();
        $provider_id = $order_product->provider_id;
        $user_id_of_this_provider =   $conProvider->getUserIdOfThisProviderId( $provider_id );

        //by lang
        $msg = LanguageTools::choose( $request, "طلب جديد",  "New Order Created");  

        //get info
        $title = "Order #".$order_product->id;
        $message  =  $msg;
        $topic = "id_".$user_id_of_this_provider;
  
        //data
        $payload = array( );;
        $payload["type"] = "order";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

        //push 
        $fcmResult =  $this->push( $topic, $title, $message, $payload );

        //save in table notification
        $notificationCont = new NotificationAdminController();
        $notificationCont->store_after_push($title, $message, $topic, $fcmResult);

        return $fcmResult;
    }

 
    public function order_to_admin( Request $request ,  OrderProduct $order_product ) {
        
        //by lang
        $msg = LanguageTools::choose( $request, "طلب جديد",  "New Order Created");  

        //get info
        $title = "Order #".$order_product->id;
        $message  =  $msg;
        $topic = "role_admin";
  
        //data
        $payload = array( );;
        $payload["type"] = "order";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

                
        //push 
        $fcmResult =  $this->push( $topic, $title, $message, $payload );

        //save in table notification
        $notificationCont = new NotificationAdminController();
        $notificationCont->store_after_push($title, $message, $topic, $fcmResult);

        return $fcmResult;
    }

    //------------------------------------------------------  type: contact us

    
    
    public function contact_us_to_admin( Request $request ,  ContactUs $contact_us ) {
        
        //get info
        $title =  $contact_us->subject;
        $message  = $contact_us->message;
        $topic = "role_admin";
  
        //data
        $payload = array( );;
        $payload["type"] = "admin";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

        //push 
       return $this->push( $topic, $title, $message, $payload );
    }

    //----------------------------------------------------- type admin Notification

    public function admin( Request $request ,  NotificationAdmin $notification_admin ) {
        
        //get info
        $title =  $notification_admin->title;
        $message  = $notification_admin->message;
        $topic = $notification_admin->topic;
  
        //data
        $payload = array( );;
        $payload["type"] = "admin";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;

        //push 
       return $this->push( $topic, $title, $message, $payload );
    }

    //----------------------------------------------------- type chat

    public function chat( Request $request , Users $userReceiverObj, ChatMessage $chat_message, String $group_key ) {
        
        //get info
        $title =  $userReceiverObj->name;
        $message  = $this->getChatMessage( $request );
        $msg_database_id = $chat_message->id;
        $sender_id = $request->senderId;
        $group_notification = $this->getChatChannelGroupId( $group_key ) ;
        $topic = $this->getChatTopicFCM( $request );
        
        //data
        $payload = array( );;
        $payload["type"] = "notify";
        $payload["topic"] = $topic; //just for debug in log
        $payload["title"] = $title;
        $payload["body"] = $message;
        $payload["msg_database_id"] = $msg_database_id;
        $payload["sender_id"] = $sender_id;
        $payload["group_notification"] = $group_notification;

        //push 
       return $this->push( $topic, $title, $message, $payload );
    }


    /**
     the channel id of notification in android just take integer not string,
    to fix this problem replace the group key underscroe "_" charactor to "00"
     */
    public function getChatChannelGroupId(String $groupKeyWithUnderScore ) : int {

        // dd( $groupKeyWithUnderScore  );

        $patterns = array();
        $patterns[0] = '/_/';

        $replacements = array();
        $replacements[0] = '00';

        $result =  preg_replace($patterns, $replacements, $groupKeyWithUnderScore);;
       // dd( $result );
        return $result;
    }


    /**
     the fcm protocol  topic example :

    
      for this function just return what after backSlash "/"
     */
    private function getChatTopicFCM(Request $request ) : String {
        return "id_".$request->receiverId;
    }

    private function getChatMessage(Request $request ) : String {
        if ( $request->image ) {
            return "photo";
        }
        if ( $request->video ) {
            return "video";
        }
        if ( $request->recorder ) {
            return "voice";
        }
        if ( $request->text ) {
            return $request->text;
        }
        return "Message";
    }

    //---------------------------------------------------------------------- push

    public function push( String $topic, String $title, String $message, $payload  ) {
        //dd( $payload );

        //for android device 
        /**
               "to" : "/topics/android_id_52",
         */
        $androidTopice = "android_".$topic;
        $resDataAndroid = $this->fcmTypeData( $androidTopice, $payload);


        //for ios
        $iosTopice = "ios_".$topic;
        $resDataAndroid = $this->fcmTypeDataWithNotification( $iosTopice,  $title,  $message,  $payload);


        return $resDataAndroid;
    }

    //------------------------------------------------------------------- types

    public function fcmTypeData( String $topic,  $payload ) {
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';
      
        // // Put your Server Response Key here
        // $apiKey = env("FCM_serverkey");

        // //check not read key
        // if( $apiKey == null    ) {
        //     $apiKey = getenv('FCM_serverkey'); 
        //     if( $apiKey == null ) {
        //         return FcmAbdallah::$serverKeyEmptyMessage;
        //     }
        // }

        //read config
        $apiKey = getenv('FCM_serverkey');
        if( $apiKey == null    ) {
            $apiKey = config('app.FCM_serverkey');
            if( $apiKey == null ) {
                return FcmAbdallah::$serverKeyEmptyMessage;
            }
        }
      
            
        //wait sleep to get api key from "env
        //usleep( 500 * 1000 );

        // Compile headers in one variable
        $headers = array (
          'Authorization:key=' . $apiKey,
          'Content-Type:application/json'
        );
      
        // Create the api body
        $apiBody = [
            'to' => '/topics/'.$topic,
            'collapse_key' => 'type_a',
            'priority' => 'high',
            'data' => $payload
          
        ];
      
        //log
       //dd( $headers );
      // dd( $apiBody );
      

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url );
        curl_setopt ($ch, CURLOPT_POST, true );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
      
         

        // Execute call and save result
        $result = curl_exec ( $ch );
      
        // Close curl after call
        curl_close ( $ch );

        /**
         * check some time the api of fcm return html not json
         * 
         * - case bad response:
             "resFCM": "<HTML>\n<HEAD>\n<TITLE>INVALID_KEY</TITLE>\n</HEAD>\n<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n<H1>INVALID_KEY</H1>\n<H2>Error 401</H2>\n</BODY>\n</HTML>\n",

         - case good response :
                 "resFCM": "{\"message_id\":1957440576283084168}",
         */
      
        return $result;
      }


      public function fcmTypeDataWithNotification( String $topic, String $title, String $message, $payload  ) {
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';
      

        //read config
        $apiKey = getenv('FCM_serverkey');
        if( $apiKey == null    ) {
            $apiKey = config('app.FCM_serverkey');
            if( $apiKey == null ) {
                return FcmAbdallah::$serverKeyEmptyMessage;
            }
        }
  
        //notification
        $notification = array( );;
        $notification["title"] = $title;
        $notification["body"] = $message;
 

        // Compile headers in one variable
        $headers = array (
          'Authorization:key=' . $apiKey,
          'Content-Type:application/json'
        );
      
        // Create the api body
        $apiBody = [
            'to' => '/topics/'.$topic,
            'collapse_key' => 'type_a',
            'priority' => 'high',
            'data' => $payload,
            'notification' =>$notification
          
        ];
      
        //log
       //dd( $headers );
     // dd( $apiBody );
      

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url );
        curl_setopt ($ch, CURLOPT_POST, true );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
      
         

        // Execute call and save result
        $result = curl_exec ( $ch );
      
        // Close curl after call
        curl_close ( $ch );

        /**
         * check some time the api of fcm return html not json
         * 
         * - case bad response:
             "resFCM": "<HTML>\n<HEAD>\n<TITLE>INVALID_KEY</TITLE>\n</HEAD>\n<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n<H1>INVALID_KEY</H1>\n<H2>Error 401</H2>\n</BODY>\n</HTML>\n",

         - case good response :
                 "resFCM": "{\"message_id\":1957440576283084168}",
         */
      
        return $result;
      }


}
 