<?php

namespace App\Http\Controllers\Application;

use App\Events\MessageSent;
use App\Events\notification;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function pusherAuth(Request $request)
    {

        $user = auth()->user();
        $socket_id = $request['socket_id'];
        $channel_name =$request['channel_name'];
        $key = getenv('PUSHER_APP_KEY');
        $secret = getenv('PUSHER_APP_SECRET');
        $app_id = getenv('PUSHER_APP_ID');

        if ($user) {

            $pusher = new Pusher($key, $secret, $app_id);
            $auth = $pusher->socket_Auth($channel_name, $socket_id);

            return response($auth, 200);

        } else {
            header('', true, 403);
            echo "Forbidden";
//            return;
        }
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */



    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($group_id)
    {
        $messages= Message::where('group_id',$group_id)->with('user')->orderBy('created_at', 'asc')->get();

        foreach($messages as $value) {
            message::where(['user_id' => \auth()->user()->id])->update(['read_at' => now()]); // if User start to see messages is_read in Table update to 0
        }
        return $messages;
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request,$group_id)
    {

        $user = Auth::user();
        $group=Group::find($group_id);

        $message = Message::create([
            'message' => $request['message'],
            'user_id'=>$user->id,
            'group_id'=>$group_id,
        ]);
        $message->sendMessage();
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $data = ['message' => $message->load('group'),'msg'=>'you have new message in ' .$message->group->name,
            'created_at' => $message->created_at->diffForHumans(),'image'=>$message->group->avatar];


        $pusher->trigger('groups.'.$message->group_id, 'notification', $data);


        broadcast(new MessageSent($message,$user))->toOthers();


           return $message->load('user');

    }

    public function isRead()
    {
        $auth = Auth::id();
        foreach (auth()->user()->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }


}
