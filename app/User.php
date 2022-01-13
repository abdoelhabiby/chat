<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','online','last_online','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];




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




}
