<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $guarded = ['*'];

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function reciver()
    {
        return $this->belongsTo(User::class,'reciver_id','id');
    }
}
