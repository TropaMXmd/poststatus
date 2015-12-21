<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Embedly;

class ProfileController extends Controller
{
    public function store( Requests\PostRequest $request){
        $content_url = $request->content_url;
        $q = Embedly::oembed($request->content_url,[
           'maxwidth' => '500'
        ]);
        if($q->error){
           return $q->error_message; //Error
        } else {
            switch($q->type){
                case 'link' :
                    $request['media_url'] = (( $q->thumbnail_url ) ? '<img src="'.$q->thumbnail_url.'"  height="'.$q->thumbnail_height.'" width="'.$q->thumbnail_width.'" />' : null);
                    break;
                case 'video' :
                    if($q->html) $request['media_url'] = $q->html;
                    elseif ($q->thumbnail_url) $request['media_url'] = '<img src="'.$q->thumbnail_url.'"  height="'.$q->thumbnail_height.'" width="'.$q->thumbnail_width.'" />';
                    else $request['media_url'] = null;
                    break;
                case 'photo' :
                    $request['media_url'] = (( $content_url ) ? '<img src="'.$q->thumbnail_url.'"  height="'.$q->thumbnail_height.'" width="'.$q->thumbnail_width.'" />' : null);
                    break;
                case 'rich':
                    $request['media_url'] = $q->html;
                    break;
                default:
                   break;
            }
            $request['title'] = (( $q->title ) ? $q->title : $content_url );
            $request['type'] = $q->type;
        }
        Post::create($request->all());
        return redirect(Auth::user()->username);
    }

    public function creatorsStatus($name,$pageNo=0){
        $user = User::where('username',strtolower($name))->first();
        $userid = $user->id;
        $username = $user->username;
        $usersStatus = $user->posts;

        $dateArray = \DB::table('posts')
                    ->select(\DB::raw("distinct date(created_at) as create_date"))
                    ->orderBy('created_at','desc')->get();

        $dates = [];
        foreach ( $dateArray as $key => $row ) {
            $dates[$key] = get_object_vars($row)['create_date'];
        }

        $contentList = null;
        $totalDateCount = count($dates);
        if($pageNo > $totalDateCount) { return view('profile.showerror')->with('errormsg',"404 Not Found"); }
        elseif($totalDateCount > 0) $contentList = \DB::select(\DB::raw("select * from posts where user_id = '$userid' and date(created_at) = '$dates[$pageNo]' order by created_at DESC"));

        return view('profile.show',compact(['contentList','dates','pageNo','totalDateCount','username','userid']));
    }
}
