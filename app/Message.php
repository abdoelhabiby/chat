<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['*'];

    protected $fillable = ['conversation_id','sender_id',
    'receiver_id',
    'text'];


    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }


    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id');
    }


    public function getCreatedAtAttribute($value)
    {
        return  \Carbon\Carbon::createFromDate($value)->diffForHumans();
    }

}
