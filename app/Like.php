<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'like'
    ];

    public function User(){
        return $this->belongsTo('App\User');
    }
    public function Post(){
        return $this->belongsTo('App\Post');
    }
    public function isLike($user_id,$post_id){
        $data = $this->where(['user_id'=> $user_id,'post_id'=>$post_id, 'like'=> true ])->first();
        if($data)return true;
        else return false;
    }
    public function isDislike($user_id,$post_id){
        $data = $this->where(['user_id'=> $user_id,'post_id'=>$post_id, 'like'=> false ])->first();
        if($data)return true;
        else return false;
    }
}
