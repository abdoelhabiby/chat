<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Http\Request;
use App\Events\TestChannelEvent;

class UserOfflineController extends Controller
{
    public function offline(User $user)
    {


    // broadcast(new TestChannelEvent($user));

    return response(['status' => 'friend'],200);


        return $user;

       $my = User::findOrFail(request()->my);

        $ids_friends = $this->getIdsFriends($my);

        // if(in_array($user->id,$ids_friends)){

        //     broadcast(new UserOfflineEvent($user));
        //     return response(['status' => 'friend'],200);

        // }


    }




    public function getIdsFriends($user) :array

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
    } // end class get friends ids


}
