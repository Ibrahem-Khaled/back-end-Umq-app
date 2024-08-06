<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\FcmAbdallah;
use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Api\Auth\ChatUserController;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Carbon\Carbon;

//timer
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{

    //-------------------------------------------------------------------  last update

    public function getSocketContinue_sender_by_me(Request $request)
    {
        try {

            //info
            // $date_string = Carbon::createFromTimestamp($request->message_greater_than_time);
            $user_id = SecurityAbdallah::getUserId();

            //query
            $chat_message = ChatMessage::
                where("id", ">", $request->message_greater_than_id)
                ->orderBy('id', 'desc')
                ->where("senderId", "=", $user_id)
            //->where("receiverId", "=", $user_id);
                ->limit(1000)
                ->get();

            return $chat_message;
        } catch (\Exception$exception) {
            return new ChatMessage();
        }
    }

    public function getSocketContinue_receiver_by_me(Request $request)
    {
        try {

            //info
            // $date_string = Carbon::createFromTimestamp($request->message_greater_than_time);
            $user_id = SecurityAbdallah::getUserId();

            //query
            $chat_message = ChatMessage::
            where("id", ">", $request->message_greater_than_id)
            //where("updated_at", ">=", $date_string)
                ->orderBy('id', 'desc')
            // ->where("senderId", "=",   $user_id)
                ->where("receiverId", "=", $user_id)
                ->limit(1000)
                ->get();

            return $chat_message;
        } catch (\Exception$exception) {
            return new ChatMessage();
        }
    }

    public function getLastUpdateWithSpecificUser(Request $request)
    {
        try {

            //get providers
            $group_key = ChatUserController::generateGroupKeyWithTarget($request->message_target_userid);

            //query
            $chat_message = ChatMessage::
                orderBy('id', 'desc')
                ->where("group_key", "=", $group_key)
                ->where("id", ">", $request->message_greater_than_id)
                ->offset($request->offset)
                ->limit(1000)
                ->get();

            return $chat_message;
        } catch (\Exception$exception) {
            return new ChatMessage();
        }
    }

    public function getLastUpdateCache(Request $request)
    {
        try {

            //get providers
            $group_key = ChatUserController::generateGroupKeyWithTarget($request->person_target);

            //time
            $date_string = Carbon::createFromTimestamp($request->start);

            //query
            $chat_message = ChatMessage::
                orderBy('id', 'desc')
                ->where("group_key", "=", $group_key)
                ->where("updated_at", ">", $date_string) // "2022-01-21 11:51:28"
                ->offset($request->offset)
                ->limit(1000)
                ->get();

            return $chat_message;
        } catch (\Exception$exception) {
            return new ChatMessage();
        }
    }

    //-------------------------------------------------------------------  get list with specific user

    public function getMessageWithSpecificUser(Request $request)
    {
        try {

            //get providers
            $group_key = ChatUserController::generateGroupKeyWithTarget($request->target);

            //query
            $chat_message = ChatMessage::
                orderBy('id', 'desc')
            // orderBy('created_at', 'desc')
            // ->orderBy('id', 'asc')
                ->where("group_key", "=", $group_key)
                ->where("deleted", "=", 0)
            // ->get();
                ->paginate($request->paginator, ['*'], 'page', $request->page);

            //get target profile
            $conUser = new UsersController();
            $userTargetSmall = $conUser->getUserIdBasicDataForChat($request->target);

            //check no record
            if ($chat_message->count() == 0) {

                return response([
                    'status' => 'No Message Found',
                    'code' => 1,
                    "first_time_chat" => 1,
                    // 'data' =>   ,  //array()
                    //  "userTarget" => null ,
                    "userTargetSmall" => $userTargetSmall,

                ], 200);
            }

            //get target profile
            /**
             * used because chat may open by "user id" without page list page users
             */
            $conUser = new ChatUserController();
            $userTarget = $conUser->getChatUserForThisTargetWithNestedObject($group_key);

            //convert status to "readed"
            if ($request->convert_status_readed == 1) {
                //loop message to change status
                foreach ($chat_message as $m) {
                    $this->validateToEditChatUpdateStatus($request, $m, "readed");
                }
            }

            if ($chat_message) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    "first_time_chat" => 0,
                    "userTarget" => $userTarget,
                    "userTargetSmall" => $userTargetSmall,
                    'data' => $chat_message,
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
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //---------------------------------------------------------------------- update status message

    public function updateStatusArrayMessageToReaded(Request $request, ChatMessage $chat_message)
    {
        //get user data
        foreach ($chat_message as $m) {
            $this->validateToEditChatUpdateStatus($request, $m, "readed");
        }

    }

    public function updateStatus(Request $request)
    {
        try {

            //get message

            $chat_message = ChatMessage::find($request->message_id);

            $input = $request->all();
            $newStatus = $input['status_read'];

            //check recorder status
            $allowChangeRecorder = $input['allowChangeRecorder'];
     

            $res = $this->validateToEditChatUpdateStatus($request, $chat_message, $newStatus, $allowChangeRecorder );

            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    //'resultUpdateTimeUser' => $resultUpdateTimeUser,
                    'data' => $chat_message,
                ], 200);
            }

            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update chat_message",
            ], 200);

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    private function validateToEditChatUpdateStatus(Request $request,
        ChatMessage $chat_message, String $newStatus, bool $allowChangeRecord = false) {

        //check privilage
        $user_id = SecurityAbdallah::getUserId();
        if ($user_id != $chat_message->receiverId) {
            // return response([
            //     'status' => 'error',
            //     'code' => 0,
            //     'data' => "Failed to update message you are not receiver user"
            //     ], 200);

           // dd( "reason 1");
            return false;
        }

        //check not to make "readed" downground to "received"
        /**
         * this may happened due to two request at sametime
         */
        if ($chat_message->status_read == "readed") {
           // dd( "reason 2");
            return false;
        }

        //check record not allowed
        if ($allowChangeRecord == false) {
            $isRecordFormat = $chat_message->recorder != null;
            //  dd($isRecordFormat);

            if ($isRecordFormat) {
                return false;
            }
        }

       //get time
        $timestampNow = Carbon::now();
        // if($request->timestamp_request != null  ) {
        //     $timestampNow = $request->timestamp_request;
        // }

        //edit
        $chat_message->status_read = $newStatus;
        $chat_message->updated_at = $timestampNow;

        //save
        $res = $chat_message->update();
        return $res;

    }

    //--------------------------------------------------------------------- create

    public function createMessageWithSpecificUser(Request $request)
    {

        try {

            //check sender id same token
            $user_id = SecurityAbdallah::getUserId();
            if ($user_id != $request->senderId) {
                return response([
                    'status' => 'failed - sender id not same user id',
                    'code' => 0,
                ], 200);
            }

            //get info
            $target = $request->receiverId;

            $group_key = ChatUserController::generateGroupKeyWithTarget($target);
            $conUser = new ChatUserController();
            $chatUser = $conUser->getChatUserForThisTarget($target);

            //create group if not found
            $newUserGroup = null;
            if ($chatUser == null) {
                $newUserGroup = $conUser->createNewUser($group_key, $target);
            }

            //check blocked
            /**
             * must check for null first to avoid exception
             */
            if ($chatUser != null && ChatUserController::isChatUserBlocked($chatUser)) {
                return response([
                    'status' => 'failed blocked',
                    'code' => 0,
                ], 200);
            }

            
            //case student chat with provider , check provider not subscribe
            $conSubcribe = new SubscribeUserController();
            $is_prevent_chat = $conSubcribe->is_student_allow_to_chat_with_this_provider($target);
           // dump("result: ".$is_prevent_chat);
            if ( $is_prevent_chat == false ) {
                return response([
                    'status' =>  "Provider Not Subscripe Yet",
                    "message" => "Provider Need The Subscription To Allow Chat",
                    'code' => 0,
                ], 200);
            }

            //case provider chat with student , check provider not subscribe
            $is_prevent_chat_with_student = $conSubcribe->is_provider_allow_to_chat_with_student($target);
            // dump("result: ".$is_prevent_chat);
             if ( $is_prevent_chat_with_student == false ) {
                 return response([
                     'status' =>  "You Need To Subscribe First To Can Take Feature Chat",
                     "message" => "Provider Need The Subscription To Allow Chat",
                     'code' => 0,
                 ], 200);
             }
             
            

            //get time
            $timestampNow = Carbon::now( );
            // if($request->timestamp_request != null  ) {
            //     $timestampNow = $request->timestamp_request;
            // }
 
            //edit model message
            $request->merge(['group_key' => $group_key]);
            $request->merge(['created_at' => $timestampNow]);
            $request->merge(['updated_at' => $timestampNow]);
            $request->merge(['status_read' => "send"]);

            //create now
            $chat_message = ChatMessage::create($request->all());
            $chat_message->save();

            //update last time table "ChatUser"
            $updateChatUserLastMessage = $conUser->updateLastTimeMessage($group_key, $timestampNow);

            //user content
            $con = new UsersController();
            $userReceiverObj = $con->getUserIdBasicDataForChat($target);

            //fcm push
            $conFCM = new FcmAbdallah();
            $resFCM = $conFCM->chat($request, $userReceiverObj, $chat_message, $group_key);

            //fcm get status 
            $fcmSuccess =  FcmAbdallah::$serverKeyEmptyMessage != $resFCM ;
            $chat_message->fcm_status = $fcmSuccess;

            //fcm update in db 
            $chat_message->fcm_message_id	= $resFCM ;
            $chat_message->update();
            
            return response([
                'status' => 'success',
                "message" => "Success Create Message",
                'code' => 1,
                'resFCM' => $resFCM,
               
                "updateChatUserLastMessage" => $updateChatUserLastMessage,
                "newUserGroup" => $newUserGroup,
                'data' => $chat_message,
                //      'userReceiver' => $userReceiverObj

            ], 200);

            // echo $jsonResult;

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    //-------------------------------------------------------------------- get single

    /**
     * $ avoid call from any where
     */
    public function getLastMessageAtGroupKey(String $group_key)
    {

        $chat_message = ChatMessage::
            orderBy('id', 'desc')
            ->where("deleted", "=", 0)
            ->where("group_key", "=", $group_key)
            ->first();

        // return $chat_message;
        if ($chat_message) {
            return $chat_message;
        } else {
            return null;
        }
    }

    //---------------------------------------------------------------- get counters

    /**
     * the badget of not reed message in chat
     */
    public function getCounterMessageNotReadWithSpecificUser(int $target): int
    {

        //get providers
        $user_id = SecurityAbdallah::getUserId();
        $group_key = ChatUserController::generateGroupKeyWithTarget($target);

        //query
        $chat_message = ChatMessage::
            orderBy('id', 'desc')
            ->where("group_key", "=", $group_key)
            ->where("deleted", "=", 0)
            ->where("receiverId", "=", $user_id) //filter the message send to me
            ->where("status_read", "!=", "readed")
            ->get();

        return $chat_message->count(); //  $target;//
    }

    //--------------------------------------------------------------------------- v1

    public function index(Request $request)
    {

        //admin only
        if (SecurityAbdallah::isUserAdmin() == false) {
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Not allowed",
            ], 200);
        }

        try {

            $chat_message = ChatMessage::
                orderBy('id', 'desc')
                ->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($chat_message) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $chat_message,
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
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            /**

            $chat_message = ChatMessage::create($request->all());
            $chat_message->save();

            return response([
            'status' => 'success',
            'code' => 1,
            'data' => $chat_message
            ], 200);
             */

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function search($search, Request $request)
    {

        try {
/**
$searchQuery = trim($search);
$requestData = ['id','text','image','video','recorder','status','deleted','senderId','receiverId','messageIdFollowed','created_at','updated_at'];
$chat_message = ChatMessage::where(function ($q) use ($requestData, $searchQuery) {
foreach ($requestData as $field)
$q->orWhere($field, 'like', "%{$searchQuery}%");
})->paginate($request->paginator, ['*'], 'page', $request->page);
if ($chat_message) {
return response([
'status' => 'success',
'code' => 1,
'data' => $chat_message
], 200);
} else {
return response([
'status' => 'error',
'code' => 0,
'data' => "No record found"
], 200);
}
 */
        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function show($id)
    {
        try {
            /**
            $chat_message = ChatMessage::where('id', '=', $id)->first();
            if ($chat_message) {
            return response([
            'status' => 'success',
            'code' => 1,
            'data' => $chat_message
            ], 200);
            } else {

            return response([
            'status' => 'error',
            'code' => 0,
            'message' => "No record found"
            ], 200);
            }
             */

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get chat_message data, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            /**
            $input = $request->all();

            $chat_message = ChatMessage::find($id);

            $chat_message->text = $input['text'];
            $chat_message->image = $input['image'];
            $chat_message->video = $input['video'];
            $chat_message->recorder = $input['recorder'];
            $chat_message->status = $input['status'];
            $chat_message->deleted = $input['deleted'];
            $chat_message->senderId = $input['senderId'];
            $chat_message->receiverId = $input['receiverId'];
            $chat_message->messageIdFollowed = $input['messageIdFollowed'];
            $chat_message->created_at = $input['created_at'];
            $chat_message->updated_at = $input['updated_at'];

            $res = $chat_message->update();
            if ($res) {
            return response([
            'status' => 'success',
            'code' => 1,
            'data' => $chat_message
            ], 200);
            }
            return response([
            'status' => 'error',
            'code' => 0,
            'data' => "Failed to update chat_message"
            ], 200);
             */

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    public function destroy($id)
    {
        try {
            /**
            $res = ChatMessage::find($id)->delete();
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
            'data' => "Failed to delete chat_message"
            ], 200);
            }
             */

        } catch (\Exception$exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete chat_message, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }
}
