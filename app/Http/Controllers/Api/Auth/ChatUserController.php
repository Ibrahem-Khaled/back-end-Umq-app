<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Abdallah\SecurityAbdallah;
use App\Http\Controllers\Controller;
use App\Models\ChatUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatUserController extends Controller
{

//------------------------------------------------------------------------ get last update

    public function getSocketContinueListUsersOrderByLastMessage(Request $request)
    {

        //time
        //  $date_string = Carbon::createFromTimestamp( $request->message_greater_than_time);

        //query
        $user_id = SecurityAbdallah::getUserId();

        $chat_user = ChatUser::
            // orderBy('id', 'desc')
            where("person_a", "=", $user_id)
            ->orWhere("person_b", "=", $user_id)
            ->orderBy('lastMessageTime', 'desc')
            ->limit($request->user_limit)
            ->get();

        // check not found
        $size = count($chat_user);
        if ($size == 0) {
            return $chat_user;
        }

        //get the lastest message
        foreach ($chat_user as $m) {
            $con = new ChatMessageController();
            $value = $con->getLastMessageAtGroupKey($m->group_key);
            $m["last_message"] = $value;
        }

        //get user data
        foreach ($chat_user as $m) {
            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
            $con = new UsersController();
            $value = $con->getUserIdBasicDataForChat($anotherUser);
            $m["user"] = $value;
        }

        //get counter not read message
        foreach ($chat_user as $m) {

            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);

            $con = new ChatMessageController();
            $value = $con->getCounterMessageNotReadWithSpecificUser($anotherUser);
            $m["counter_not_read"] = $value;

        }

        return $chat_user;
    }

    public function getLastUpdateWithSpecificUser(Request $request)
    {

        //query
        $user_id = SecurityAbdallah::getUserId();

        $chat_user = ChatUser::
            // orderBy('id', 'desc')
            where("person_a", "=", $user_id)
            ->orWhere("person_b", "=", $user_id)
            ->orderBy('lastMessageTime', 'desc')
            ->limit($request->user_limit)
            ->get();

        // check not found
        $size = count($chat_user);
        if ($size == 0) {
            return $chat_user;
        }

        //get the lastest message
        foreach ($chat_user as $m) {
            $con = new ChatMessageController();
            $value = $con->getLastMessageAtGroupKey($m->group_key);
            $m["last_message"] = $value;
        }

        //get user data
        foreach ($chat_user as $m) {
            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
            $con = new UsersController();
            $value = $con->getUserIdBasicDataForChat($anotherUser);
            $m["user"] = $value;
        }

        //get counter not read message
        foreach ($chat_user as $m) {

            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);

            $con = new ChatMessageController();
            $value = $con->getCounterMessageNotReadWithSpecificUser($anotherUser);
            $m["counter_not_read"] = $value;

        }

        return $chat_user;
    }

    public function getLastUpdateCache(Request $request)
    {

        //time
        $date_string = Carbon::createFromTimestamp($request->start);

        //query
        $user_id = SecurityAbdallah::getUserId();

        $chat_user = ChatUser::
            orderBy('id', 'desc')
            ->where("person_a", "=", $user_id)
            ->orWhere("person_b", "=", $user_id)
            ->orderBy('lastMessageTime', 'desc')
            ->where("lastMessageTime", ">", $date_string)
            ->offset($request->offset)
            ->limit(1000)
            ->get();

        // check not found
        $size = count($chat_user);
        if ($size == 0) {
            return $chat_user;
        }

        //get the lastest message
        foreach ($chat_user as $m) {
            $con = new ChatMessageController();
            $value = $con->getLastMessageAtGroupKey($m->group_key);
            $m["last_message"] = $value;
        }

        //get user data
        foreach ($chat_user as $m) {
            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
            $con = new UsersController();
            $value = $con->getUserIdBasicDataForChat($anotherUser);
            $m["user"] = $value;
        }

        //get counter not read message
        foreach ($chat_user as $m) {

            $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);

            $con = new ChatMessageController();
            $value = $con->getCounterMessageNotReadWithSpecificUser($anotherUser);
            $m["counter_not_read"] = $value;

        }

        return $chat_user;
    }

    //---------------------------------------------------------------- get array chat with me users

/**
 * return list of users who create chate with me before
 */
    public function chatWithMeUsers(Request $request)
    {
        try {

            //query
            $user_id = SecurityAbdallah::getUserId();
            $chat_user = ChatUser::
                where("person_a", "=", $user_id)
                ->orWhere("person_b", "=", $user_id)
                ->orderBy('lastMessageTime', 'desc')
                ->paginate($request->paginator, ['*'], 'page', $request->page);

            // check not found
            $size = count($chat_user);
            if ($size == 0) {
                return response([
                    'status' => 'No Chat at history',
                    'code' => 0
                ], 200);
            }

            //get the lastest message
            foreach ($chat_user as $m) {
                $con = new ChatMessageController();
                $value = $con->getLastMessageAtGroupKey($m->group_key);

                if($value == null ) {continue;}

                $m["last_message"] = $value;
            }

            //get user data
            foreach ($chat_user as $m) {
                $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
                $con = new UsersController();
                $value = $con->getUserIdBasicDataForChat($anotherUser);

                //check user deleted
                if($value == null ) {continue;}
                $m["user"] = $value;
            }

            //get counter not read message
            foreach ($chat_user as $m) {
                $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
                $con = new ChatMessageController();
                $value = $con->getCounterMessageNotReadWithSpecificUser($anotherUser);
                $m["counter_not_read"] = $value;
            }

            //navigate to specific user
            $navigate_user = null;
            if ($request->navigate_target_id != null) {
                $group_key_navigate = $this->generateGroupKeyWithTarget( $request->navigate_target_id );
                $navigate_user = $this->getChatUserForThisTargetWithNestedObject($group_key_navigate);
            }

            //return
            if ($chat_user) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                 
                    "data" => $chat_user,
                    'navigate_user' => $navigate_user,
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
                'message' => "Failed to get chat_user, please try again. {$exception->getMessage()}",
            ], 200);
        }
    }

    


    //---------------------------------------------------------------------- check block

    /**
    means you not allowed to send message,
    1- case: admin make block to contact between two users
    2- case: person "a" blocker is person, so when "person b" try to send message the process failed

    - return value: true cause blocked happened.
     */
    public static function isChatUserBlocked(ChatUser $obj): bool
    {
        if ($obj == null) {return false;}

        //blocked by admin
        if ($obj->blocker_admin == 1) {return true;}

        //blocked by person
        // $user_id = SecurityAbdallah::getUserId();
        $isIamPersonA = ChatUserController::isIamPersonA($obj->person_a);
        if ($isIamPersonA) {
            /*
            case: person "a" blocker is person, so when "persion b" try to send message, he failed
             */
            return $obj->blocker_person_b;
        } else {
            return $obj->blocker_person_a;
        }
    }

//-------------------------------------------------------------------------- get  single

    public function getChatUserForThisTarget(String $target)
    {
        $group_key = ChatUserController::generateGroupKeyWithTarget($target);
        $chat_user = ChatUser::
            where("group_key", "=", $group_key)
            ->first();

        return $chat_user;
    }

    public function getChatUserForThisTargetWithNestedObject(String $group_key) { // : ChatUser 

        try { 
            
        $m = ChatUser::
        where("group_key", "=", $group_key)
        ->first();

        //check not found this group
        if( $m == null ) {
            return null;
        }

    //get the lastest message
    $con = new ChatMessageController();
    $value = $con->getLastMessageAtGroupKey($m->group_key);
    $m["last_message"] = $value;

    //get user data

    $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
    $con = new UsersController();
    $value = $con->getUserIdBasicDataForChat($anotherUser);
    $m["user"] = $value;

    //get counter not read message

    $anotherUser = ChatUserController::getAnotherUserIdChattingWithHim($m->person_a, $m->person_b);
    $con = new ChatMessageController();
    $value = $con->getCounterMessageNotReadWithSpecificUser($anotherUser);
    $m["counter_not_read"] = $value;

    //   dd( $m );
    return $m;
    } catch (\Exception$exception) {

        return null;

            // return response([
            //     'status' => 'error',
            //     'code' => 0,
            //     'message' => "Failed to get chat_message, please try again. {$exception->getMessage()}",
            // ], 200);
        }
    }

    //-------------------------------------------------------------------------- create

    public static function createNewUser(String $group_key, String $target): ChatUser
    {
        try {

            //get data
            $user_id = SecurityAbdallah::getUserId();
            $isIamPersonA = ChatUserController::isIamPersonA($target);

            //set values of person
            $person_a = "";
            $person_b = "";
            if ($isIamPersonA) {
                /**
                 * Rule of generate "group_key" :
                 * set the small user_id in person "a"
                 */
                $person_a = $user_id;
                $person_b = $target;
            } else {
                $person_a = $target;
                $person_b = $user_id;
            }

            //create
            $chat_user = ChatUser::create([
                'person_a' => $person_a,
                'person_b' => $person_b,
                'group_key' => $group_key,
            ]);
            $chat_user->save();

            return $chat_user;
        } catch (\Exception$exception) {
            return null;
        }
    }

    //----------------------------------------------------------------------------- update

    public function updateLastTimeMessage(String $group_key, String $value)
    {
        try {

            //get data
            $chat_user = ChatUser::where('group_key', '=', $group_key)->first();

            //edit
            $chat_user->lastMessageTime = $value; //Carbon::now();

            //save
            $res = $chat_user->update();

            return $res;
        } catch (\Exception$exception) {
            return null;
        }
    }
    //--------------------------------------------------------------------------- tools: group key

    public static function generateGroupKeyWithTarget(int $target): String
    {
        $user_id = SecurityAbdallah::getUserId();
        return ChatUserController::generateGroupKey($user_id, $target);
    }

    /**
    the rule of generate group key between two individual:
    -  make the person a is the smaller id value. + "-" + biggerUserId

    - example: person a user id = 4, person b user id = 6
    now the resutl "4_6"

     */
    public static function generateGroupKey(int $a, int $b): String
    {
        if ($a < $b) {
            return "" . $a . "_" . $b;
        } else {
            return "" . $b . "_" . $a;
        }
    }

    //-------------------------------------------------------------------------- tools : persons (a,b)

    /**
    seach which i am in persoin 'a" or person "b" then return another one.
    example: abdo = a, ahmed = b. now when abdo get list of users, he need to retunr persoin b id
     */
    public static function getAnotherUserIdChattingWithHim(String $person_a, String $person_b): String
    {
        $isIamPersonA = ChatUserController::isIamPersonA($person_a);
        if ($isIamPersonA) {
            return $person_b;
        } else {
            return $person_a;
        }
    }

    public static function isIamPersonA(String $person_a): bool
    {
        $user_id = SecurityAbdallah::getUserId();
        return $user_id == $person_a;
    }

    public static function isIamPersonB(String $person_b): bool
    {
        $user_id = SecurityAbdallah::getUserId();
        return $user_id == $person_b;
    }

    //-------------------------------------------------------------------------- v1

    /**


    public function index(Request $request)
    {
    try {

    $chat_user = ChatUser::
    orderBy('id', 'desc')
    ->paginate($request->paginator, ['*'], 'page', $request->page);
    if ($chat_user) {
    return response([
    'status' => 'success',
    'code' => 1,
    'data' => $chat_user,
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
    'message' => "Failed to get chat_user, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }


    public function store(Request $request)
    {
    try {
    $chat_user = ChatUser::create($request->all());
    $chat_user->save();

    return response([
    'status' => 'success',
    'code' => 1,
    'data' => $chat_user,
    ], 200);
    } catch (\Exception$exception) {
    return response([
    'status' => 'error',
    'code' => 0,
    'message' => "Failed to store chat_user, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }

    public function search($search, Request $request)
    {

    //  $requestData = ['id','person_a','person_b','group_key','group_type','lastMessageTime'];

    try {

    $searchQuery = trim($search);
    $requestData = ['person_a', 'person_b', 'group_key'];
    $chat_user = ChatUser::where(function ($q) use ($requestData, $searchQuery) {
    foreach ($requestData as $field) {
    $q->orWhere($field, 'like', "%{$searchQuery}%");
    }

    })->paginate($request->paginator, ['*'], 'page', $request->page);

    if ($chat_user) {
    return response([
    'status' => 'success',
    'code' => 1,
    'data' => $chat_user,
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
    'message' => "Failed to get chat_user, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }

    public function show($id)
    {
    try {
    $chat_user = ChatUser::where('id', '=', $id)->first();
    if ($chat_user) {
    return response([
    'status' => 'success',
    'code' => 1,
    'data' => $chat_user,
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
    'message' => "Failed to get chat_user data, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }


    public function edit($id)
    {
    //
    }


    public function update(Request $request, $id)
    {
    try {
    $input = $request->all();

    $chat_user = ChatUser::find($id);

    $chat_user->person_a = $input['person_a'];
    $chat_user->person_b = $input['person_b'];
    $chat_user->group_key = $input['group_key'];
    $chat_user->group_type = $input['group_type'];
    $chat_user->lastMessageTime = $input['lastMessageTime'];

    $res = $chat_user->update();
    if ($res) {
    return response([
    'status' => 'success',
    'code' => 1,
    'data' => $chat_user,
    ], 200);
    }
    return response([
    'status' => 'error',
    'code' => 0,
    'data' => "Failed to update chat_user",
    ], 500);
    } catch (\Exception$exception) {
    return response([
    'status' => 'error',
    'code' => 0,
    'message' => "Failed to update chat_user, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }


    public function destroy($id)
    {
    try {
    $res = ChatUser::find($id)->delete();
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
    'data' => "Failed to delete chat_user",
    ], 500);
    }
    } catch (\Exception$exception) {
    return response([
    'status' => 'error',
    'code' => 0,
    'message' => "Failed to delete chat_user, please try again. {$exception->getMessage()}",
    ], 500);
    }
    }

     */

}
