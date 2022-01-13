<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['*'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
