<?php

namespace App\Http\Controllers;


use App\Comment;
use App\Post;
use App\User;
use Carbon\Carbon;
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
    public function storeComment(Requests\CommentRequest $request,$name){
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $comment = Post::find($request->post_id)->comments()->save(Comment::create($data));
        return View("partials.comment",compact('comment'))->render();
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