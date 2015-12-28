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
        $user = new User();
        $userID = $user->getUserID(strtolower($name));
        return $userID;
    }

    //------STORE RECENT POST--------------
    public function storePost(Requests\PostRequest $request,$name){
        $post = Post::create($request->all());
        return View("partials.post",compact('post','name'))->render();
    }

    //------STORE COMMENT-------------------
    public function storeComment(Requests\CommentRequest $request,$name){
        //Comment::create($request->all());
        //return redirect($request->username);
        return 'yes';
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
        dd($posts);
//        $posts = Post::where('user_id',$this->getUserID($name))
//            ->whereDate('created_at', '=', $data['date'])
//            ->orderBy('created_at', 'DESC')->get();

        //$posts = getPosts($data['date'],$data['userID']);
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