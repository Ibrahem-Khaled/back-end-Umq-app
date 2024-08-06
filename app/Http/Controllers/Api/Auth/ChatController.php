<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\JsonResourceAbdallah;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

 
/**
 * @author : abdallah
 *  this class to controle the chat message and chat users
 */
class ChatController extends Controller
{

    //-------------------------------------------------------------- socket continue

    public function getSocketContinue(Request $request)
    {
        try {

            return  $this->getSingleSocketUpdates($request, $request->index_loop  );
            

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "getSocketContinue() - Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }

    }


    public function getSingleSocketUpdates(Request $request, $indexLoop = 0 )
    {
        try {

            //get data from many tables
            $conUser = new ChatUserController();
            $conMessage = new ChatMessageController();
            $arrayUsers = $conUser->getSocketContinueListUsersOrderByLastMessage($request);
            $arrayMessage_sender = $conMessage->getSocketContinue_sender_by_me($request);
            $arrayMessage_receiver = $conMessage->getSocketContinue_receiver_by_me($request);

            //check have message recieved
            $sizeMessage_receiver = $arrayMessage_receiver->count();
            $sizeMessage_sender = $arrayMessage_sender->count();
            if ($sizeMessage_receiver == 0 &&
                $sizeMessage_sender == 0
            ) {
                //json success found data
                return response([
                    'indexLoop' => $indexLoop,
                    'status' => 'failed',
                    'code' => 1,
                    'msg' => "No updates found"
                ], 200);
             // return JsonResourceAbdallah::failedShapeArray(  'indexLoop',  $indexLoop );
            }

            //push to array message to geather
            $arrayMessage_all = array();
            for($i = 0; $i < count($arrayMessage_receiver); $i++) {
                $m = $arrayMessage_receiver[ $i ];
                $arrayMessage_all[] = $m;  
            }

            for($i = 0; $i < count($arrayMessage_sender); $i++) {
                $m = $arrayMessage_sender[ $i ];
                $arrayMessage_all[] = $m;  
            }

            //first item, it's have the last updated message
            $latestMessageId = $arrayMessage_all[0]->id;
   
            //put inside dictionary
            $body = array();
            $body["indexLoop"] = $indexLoop;
            $body["next_socket_search_for_greater_than_id"] = $latestMessageId;
            $body["message_update"] = $arrayMessage_all;
            // $body["message_update"] = $arrayMessage_receiver;
            // $body["message_sender"] = $arrayMessage_sender;
            $body["user_update"] = $arrayUsers;

            //json success found data
            return JsonResourceAbdallah::array($body);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'indexLoop' => $indexLoop,
                'code' => 0,
                'message' => "getSingleSocketUpdates() - Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //--------------------------------------------------------------- last update with specific user

    public function getLastUpdateWithSpecificUser(Request $request)
    {

        //check not have time

        try {

            //get data from many tables
            $conUser = new ChatUserController();
            $conMessage = new ChatMessageController();
            $arrayUsers = $conUser->getLastUpdateWithSpecificUser($request);
            $arrayMessage = $conMessage->getLastUpdateWithSpecificUser($request);

            //put inside dictionary
            $body = array();
            $body["user_update"] = $arrayUsers;
            $body["message_update"] = $arrayMessage;

            //json
            return JsonResourceAbdallah::array($body);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //--------------------------------------------------------------- last update : type cache
    /**
     * type online means mobile save all messsage offline, then need to query to get the last update
     * after specifice timestamp
     */

    /**
     * return last update Chat user + last update message
     */
    public function getLastUpdateCache(Request $request)
    {

        //check not have time

        try {

            //get data from many tables
            $conUser = new ChatUserController();
            $conMessage = new ChatMessageController();
            $arrayUsers = $conUser->getLastUpdateCache($request);
            $arrayMessage = $conMessage->getLastUpdateCache($request);

            //put inside dictionary
            $body = array();
            $body["start"] = $request->start;
            $body["offset"] = $request->offset;
            $body["user_update"] = $arrayUsers;
            $body["message_update"] = $arrayMessage;

            //json
            return JsonResourceAbdallah::array($body);
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

}
