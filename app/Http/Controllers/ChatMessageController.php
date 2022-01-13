<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\UserMessage;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{


    public function fetchChatMessages(User $friend)
    {

        $messages_table = Message::where('parent_id',3)->first();
        $user = auth()->user();

        $chat_messages_1 = UserMessage::where('sender_id',$user->id)->where('receiver_id',$friend->id)->get();

        return $messages_table;
    }



}
