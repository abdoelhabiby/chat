<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {





         $friends_id = $this->getIdsFriends();




         $friends = User::select('id','email','name','online','last_online')->whereIn('id',$friends_id )->get();
         $friends_online = User::select('id','email','name','online','last_online')->whereIn('id',$friends_id )->where('online',true)->get();

         return view('chat.index',compact(['friends','friends_online']));
    }






    public function getIdsFriends() :array

    {
        $user = auth()->user();

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



}  // end of class
