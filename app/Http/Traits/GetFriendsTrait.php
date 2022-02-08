<?php

namespace App\Http\Traits;

use App\User;
use App\Friend;

trait GetFriendsTrait
{

    public function getIdsFriends(User $user) : array
    {

        $friends_id = Friend::select('sender_id', 'reciver_id')
        ->where('sender_id', $user->id)
        ->orWhere('reciver_id', $user->id)
        ->get();


    $ids = [];

    foreach ($friends_id as $friend) {

        if ($friend->reciver_id != $user->id) {
            $ids[] = $friend->reciver_id;
        }

        if ($friend->sender_id != $user->id) {
            $ids[] = $friend->sender_id;
        }
    }

    return  $ids;
    }

}
