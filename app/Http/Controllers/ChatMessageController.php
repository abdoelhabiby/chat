<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\Conversation;
use Illuminate\Http\Request;
use App\Events\NewMessageEvent;
use App\Http\Traits\GetFriendsTrait;
use App\Http\Traits\GetConversationTrait;
use COM;
use Illuminate\Support\Facades\Validator;

class ChatMessageController extends Controller
{

    use GetFriendsTrait, GetConversationTrait;

    public function fetchChatMessages(User $friend)
    {

         $user = auth()->user();
        $conversation = $this->getConversationOrCreate($user, $friend);

        $chat_messgaes = Message::where('conversation_id', $conversation->id)
            ->latest()
            ->paginate(5);


        $messages = $chat_messgaes->reverse()->values();


        $last_online = \Carbon\Carbon::createFromDate($friend->last_online)->diffForHumans();

        $pagination_details = [
            'total_records' => $chat_messgaes->total(),
            'next_page_url' => $chat_messgaes->nextPageUrl(),
            'has_pages' => $chat_messgaes->hasPages(),
        ];

        $data = [
            'name' => $friend->name,
            'email' => $friend->email,
            'online' => $friend->online,
            'last_online' => $last_online,
            'chat_messages' => $messages,
            'pagination_details' => $pagination_details
        ];


        return response($data);

        return response('get  chat messages', 200);
    }




    private function checkIfFriend($id)
    {
        $friends_id = $this->getIdsFriends(auth()->user());

        if (in_array($id, $friends_id)) {
            return true;
        }

        return false;
    }




    public function checkFriendConversation(Conversation $conversation, User $friend)
    {
        $user = auth()->user();


        if ($conversation->user_1 == $user->id && $conversation->user_2 == $friend->id) {
            return true;
        }

        if ($conversation->user_2 == $user->id && $conversation->user_1 == $friend->id) {
            return true;
        }

        return false;
    }

    //-------------------------------------

    public function saveMessage(Conversation $conversation, User $friend, Request $request)
    {
        $user = auth()->user();

        $chekFriendconversation = $this->checkFriendConversation($conversation, $friend);

        if (!$chekFriendconversation) {
            return response(['err' => 'not ffff'], 403);
        }

        $validated = Validator::make($request->all(), [
            'message' => 'required|string|max:400'

        ]);



        if ($validated->fails()) {
            // return 'fails';
            return response(['fails' => 'add valid adata'], 400);
        }


        $text_message =  $validated->validated()['message'];

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'text' => $text_message

        ]);


        broadcast(new NewMessageEvent($user, $friend, $conversation, $message))->toOthers();
        return response(['status' => 'success send message']);
    }



    public function conversation($friend)
    {
        $user = auth()->user();
        $friend = User::select([
            'id', 'name', 'email', 'online', 'last_online'
        ])->findOrFail($friend);

        $friend->last_online = \Carbon\Carbon::createFromDate($friend->last_online)->diffForHumans();

        $conversation = $this->getConversationOrCreate($user, $friend);

        $data = ['friend' => $friend, 'conversation_id' => $conversation->id];

        return response($data);
    }
}
