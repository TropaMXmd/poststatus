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
    public function postOwner(){
        return $this->belongsTo('App\User');
    }
}
