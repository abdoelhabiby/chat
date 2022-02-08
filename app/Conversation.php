<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_1', 'user_2'
    ];


    public function userOne(){
        return $this->belongsTo(User::class,'user_1','id');
    }
    public function userTwo(){
        return $this->belongsTo(User::class,'user_2','id');
    }

}
