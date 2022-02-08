<?php

namespace App\Http\Traits;

use App\User;
use App\Friend;
use App\Conversation;

trait GetConversationTrait
{

    public function getConversationOrCreate(User $user, User $friend)
    {

        $conversation = Conversation::where('user_1', $user->id)
            ->where('user_2', $friend->id)
            ->orWhere('user_1', $friend->id)
            ->where('user_2', $user->id)
            ->first();


        if (!$conversation) {
            $conversation = Conversation::create([
                'user_1' => $user->id,
                'user_2' => $friend->id
            ]);
        }

        return $conversation;
    }
}
