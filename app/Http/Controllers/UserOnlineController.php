<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Http\Request;
use App\Events\UserOnlineEvent;

class UserOnlineController extends Controller
{


    public function online(User $user)
    {


       $my = User::findOrFail(request()->my);

        $ids_friends = $this->getIdsFriends($my);

        if(in_array($user->id,$ids_friends)){

            broadcast(new UserOnlineEvent($user));
            return response(['status' => 'friend'],200);

        }


    }




    public function getIdsFriends($user) :array

    {

        // or we can add query to sender = $user->id and another query  reciver = $user->id  and merge

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
    } // end class get friends ids





}
