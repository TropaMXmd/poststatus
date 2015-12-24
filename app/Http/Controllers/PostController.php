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

class PostController extends Controller
{
    public function store(Requests\PostRequest $request,$name){

      $content = Post::create($request->all());
        return view('partials.post',compact('content'));
      //$this->creatorsPosts($name);
    }

    public function storeComment(Requests\CommentRequest $request){
        Comment::create($request->all());
        return redirect($request->username);
    }

    public function creatorsPosts($name,$pageNo=0){



//        $user = User::where('username',strtolower($name))->first();
//        $dt = Carbon::today();
//        $now = $dt->now()->toDateTimeString();
//        //dd($user->posts->lists('created_at'));
//        $status = $user->PullStatusByDateRange($dt->subDay($pageNo)->toDateTimeString(),$now);

        $user = User::where('username',strtolower($name))->first();

        $userid = $user->id;
        $username = $user->username;
        $dateArray = \DB::table('posts')
            ->select(\DB::raw("distinct date(created_at) as create_date"))
            ->orderBy('created_at','desc')->get();

        $dates = [];
        foreach ( $dateArray as $key => $row ) {
            $dates[$key] = get_object_vars($row)['create_date'];
        }

        $contentList = null;
        $totalDateCount = count($dates);
        if($pageNo > $totalDateCount) { return view('post.showerror')->with('errormsg',"404 Not Found"); }
        elseif($totalDateCount > 0){
            $contentList = \DB::select(\DB::raw("select * from posts where user_id = '$userid' and date(created_at) = '$dates[$pageNo]' order by created_at DESC"));
        }
        $comments = [];
        if(count($contentList) > 0){
            foreach($contentList as $key => $post){
                $post = Post::find($post->id);
                $comments[$key] = $post->comments;
            }
        }
        return view('post.show',compact(['contentList','dates','pageNo','totalDateCount','username','userid','comments']));
    }
}