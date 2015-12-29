<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'content_url',
        'media_url',
        'title',
        'type',
        'user_id'
    ];


    //a status is owned by a user
    public function user(){
        return $this->belongsTo('App\User');
    }
    //a post may have many comments
    public function Comments(){
        return $this->hasMany('App\Comment');
    }
    //a post may have many likes
    public function Likes(){
        return $this->hasMany('App\Like')->where('likes.like', '=', true)->get();
    }
    //a post may have many dislikes
    public function Dislikes(){
        return $this->hasMany('App\Like')->where('likes.like', '=', false)->get();
    }
}
