<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        'user_id',
        'post_id'
    ];


    //a comment is owned by a user
    public function user(){
        return $this->belongsTo('App\User');
    }
    //a comment is owned by a post
    public function post(){
        return $this->belongsTo('App\Post');
    }
}
