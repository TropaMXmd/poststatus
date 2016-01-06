<?php

namespace App\Http\Controllers;


use App\Comment;
use App\Like;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Embedly;
use Input;
use View;
use Response;


class PostController extends Controller
{
    public function __construct()
    {
        App::setLocale(Session::get('my.locale', Config::get('app.locale')));
    }
    //------GET USER ID FROM USERNAME-----
    public function getUserID($name){
        $user = User::where('username', $name)->first();
        return $user->id;
    }

    //------STORE RECENT POST--------------
    public function storePost(Requests\PostRequest $request,$name){
        $post = Post::create($request->all());
        return View("partials.post",compact('post','name'))->render();
    }

    //------STORE COMMENT-------------------
    /**
     * @param Requests\CommentRequest $request
     * @param $name
     * @return mixed
     */
    public function storeComment(Requests\CommentRequest $request,$name){
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $comment = Post::find($request->post_id)->comments()->save(Comment::create($data));

        //send mail
        $data = array(
            'name' => "Learning Laravel",
        );

        Mail::send('email.welcome', $data, function ($message) {

            $message->from('developer.ewmgl@gmail.com', 'Learning Laravel');

            $message->to('tropa.mahmood@gmail.com')->subject('Learning Laravel test email');

        });

        return View("partials.comment",compact('comment'))->render();
    }

    //------STORE LIKE------------------
    public function storeLike(Request $request,$name){
        $data = $request->all();
        if($data['like'] == "true") $data['like'] = true;
        else $data['like'] = false;
        $data['user_id'] = Auth::user()->id;
        //CHECK WHETHER LIKED OR DISLIKED AND REACT ACCORDINGLY
        $likeObject = new Like();
        $isLike = $likeObject->isLike($data['user_id'],$data['post_id']);
        $isDislike = $likeObject->isDislike($data['user_id'],$data['post_id']);
        if(!$isLike && !$isDislike){ //if not liked and not disliked
            Like::create($data);
        }elseif($isLike && !$isDislike && $data['like']== true){ //already liked,not disliked,pressed like button
            //dd("already liked,not disliked,pressed like button");
            Like::where(['post_id'=>$data['post_id'],'user_id'=>$data['user_id']])->delete();
        }elseif($isLike && !$isDislike && $data['like']== false){ //already liked,not disliked,but pressed dislike button
            //dd("already liked,not disliked,but pressed dislike button");
            Like::where(['post_id'=>$data['post_id'],'user_id'=>$data['user_id']])->update(array('like' => false));
        }elseif(!$isLike && $isDislike && $data['like']== true){ //not liked,already disliked,but pressed like button
            //dd("not liked,already disliked,but pressed like button");
            Like::where(['post_id'=>$data['post_id'],'user_id'=>$data['user_id']])->update(array('like' => true));
        }elseif(!$isLike && $isDislike && $data['like']== false){ //not liked,already disliked,but pressed dislike button
            //dd("not liked,already disliked,but pressed dislike button");
            Like::where(['post_id'=>$data['post_id'],'user_id'=>$data['user_id']])->delete();
        }

        $post = Post::find($data['post_id']);
        $countLikeDislike = null;
        $countLikeDislike['likes'] = $post->Likes()->count();
        $countLikeDislike['dislikes'] = $post->Dislikes()->count();
        $countLikeDislike['icon_flag'] = $data['like'];
        return $countLikeDislike;
    }

    //-----SHOW ALL POSTS ON A SINGLE DAY----
    public function showPosts(Request $request,$name){
        $data = $request->all();
        $user = User::where('username', $name)->first();
        if($user)
        {
            $posts = $user->posts()->whereDate('created_at', '=', $data['date'])
                ->orderBy('created_at', 'DESC')->get();;
        }

        return View("partials.posts",compact('posts','name'))->render();

    }

    //------PASS THE DATE TO HOME VIEW-------
    public function creatorsPosts($name){
        $posts = null;
        $post = null;
        $userID = $this->getUserID($name);

        $dateObject = \DB::table('posts')
            ->select(\DB::raw("distinct date(created_at) as create_date"))
            ->where('user_id',$userID)
            ->orderBy('created_at','desc')->get();

        return view('post.showpost',compact('dateObject','name','userID','posts','post'));
    }
}