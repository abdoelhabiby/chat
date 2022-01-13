<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }




    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
