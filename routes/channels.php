<?php

use App\Friend;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});




Broadcast::channel('channel_new_notification.{userId}', function ($user) {

    return auth()->user()->id == $user->id;
});


Broadcast::channel('chat',function($user){

      return $user;
});



Broadcast::channel('chat_room',function($user){

      return $user ? true : false;
});


Broadcast::channel('online', function ($user) {



    $user_o = auth()->user();

    $friends_id = Friend::select('sender_id', 'reciver_id')
        ->where('sender_id', $user_o->id)
        ->orWhere('reciver_id', $user_o->id)
        ->get();


    $ids = [];

    foreach ($friends_id as $friend) {

        if ($friend->reciver_id != $user_o->id) {
            $ids[] = $friend->reciver_id;
        }

        if ($friend->sender_id != $user_o->id) {
            $ids[] = $friend->sender_id;
        }
    }


return $user;
     if( in_array($user->id,$ids) ){
         return $user;
     }




    return false;
    return $user->name;
    // return $user->name == "mohamed" ? 'my friend' : "not friend";
});


