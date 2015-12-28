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
    public function getPosts($date,$userID){
        $posts = $this->where('user_id',$userID)
                    ->whereDate('created_at', '=', $date)
                    ->orderBy('created_at', 'DESC')->get();
        return $posts;
    }
}
