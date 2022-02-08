<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Friend;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {


        $user = auth()->user();



          $conversations = Conversation::where('user_1', $user->id)
                                       ->orWhere('user_2', $user->id)
                                       ->select(['id','user_1','user_2'])
                                       ->get();

            $friends_id = $this->getFriendsIds($conversations,$user);

            $friends_chat = User::whereIn('id',$friends_id)->select([
                'id','name', 'email','online','last_online'
            ])->get();

          $friends = $friends_chat;

         return view('chat.index',compact(['friends']));
    }



    private function filterFriendDetalis(Friend $room_details,User $user)
    {
        return [
              "room_id" => $room_details->id,
              "sender_id" => $room_details->sender_id,
              "reciver_id" => $room_details->reciver_id,
              'user_id' => $user->id ,
              'name' => $user->name ,
              'email' => $user->email ,
              'online' => $user->online ,
              'last_online' => $user->last_online
        ];

    }



    public function getFriendsIds( $conversations,User $user)
    {

        $ids = [];

        foreach ($conversations as $conversation) {

            if ($conversation->user_1 != $user->id) {
                $ids[] = $conversation->user_1;
            }

            if ($conversation->user_2 != $user->id) {
                $ids[] = $conversation->user_2;
            }
        }

        return  $ids;
    }



}  // end of class
