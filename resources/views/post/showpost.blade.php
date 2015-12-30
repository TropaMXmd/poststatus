@extends('app')

@section('content')

    <h1 style="color:green;">
        {{ ucfirst ($name) }}'s Profile
    </h1>
    </br>
    @if ( Auth::guest() == false && Auth::user()->id == $userID )
        <hr/>

        {!! Form::open(array('url'=> $name ,'method'=>'POST', 'id'=>'postform')) !!}
        @include('post.form',['submitButtonText' => 'Share Link'])
        {!! Form::close() !!}


        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
   <hr/>
   <img class="loading-img" src="/images/loading.gif" style="display:none;" />
   <div class="content">
       @include('partials.post',compact('post'))
       @include('partials.posts',compact('posts','name'))
   </div>
   @if($dateObject)
       @foreach($dateObject as $key => $date)
           <a class="page" href="#" data-value="{{ $date->create_date }}">{{ $key+1 }}</a>
       @endforeach
   @endif
@endsection
@section('footerjs')
   <script>
       var username = "{{$name}}";
       var userID = "{{ $userID }}";
       $(document).ready(function(){
           $('.page').click(function(){
//               var i=$(this).data("value");
//               //console.log(i);
               $('.loading-img').show();
               $.ajax({
                   url: username,
                   type: "post",
                   data: {
                       'date': $(this).data("value"),
                       'userID' : userID,
                       '_token':$('meta[name=csrf-token]').attr("content"),
                   },
                   success: function(response){
                       $('.loading-img').hide();
                       $('.content').html(response);
                   }
               });
           });
           function ajaxForLike(post_id,like){
               $.ajax({
                   url: username+'/storelike',
                   type: "post",
                   data: {
                       'post_id':post_id,
                       'like':like,
                       '_token': $('meta[name=csrf-token]').attr("content"),
                   },
                   success: function(response){
                       if(response['icon_flag']){
                           if($('.post-dislike').find($(".fa")).hasClass('fa-thumbs-down'))
                           {
                               console.log('like');
                               $('.post-dislike').find($(".fa")).removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down');
                           }
                       }else{
                           if($('.post-like').find($(".fa")).hasClass('fa-thumbs-up'))
                           {
                               console.log('dislike');
                               $('.post-like').find($(".fa")).removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                           }
                       }

                       if(response['likes'] > 0 && response['dislikes'] > 0){
                           $('.showlikes').html("<a >"+response['likes']+" Likes</a><a> ,"+response['dislikes']+" Dislikes</a>");
                       }else if(response['likes'] > 0 && response['dislikes'] == 0){
                           $('.showlikes').html("<a >"+response['likes']+" Likes</a>");
                       }else if(response['likes'] == 0 && response['dislikes'] > 0){
                           $('.showlikes').html("<a >"+response['dislikes']+" Dislikes</a>");
                       }else if(response['likes'] == 0 && response['dislikes'] == 0){
                           $('.showlikes').html('');
                       }
                   }
               });
           }
           $('body').on('click', '.post-like', function(e) {
               if($(this).find($(".fa")).hasClass('fa-thumbs-up'))
               {
                   $(this).find($(".fa")).removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
               }
               else if($(this).find($(".fa")).hasClass('fa-thumbs-o-up'))
               {
                   $(this).find($(".fa")).removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
               }
               var post_id = $(this).data("value");
               var like = true;
               ajaxForLike(post_id,like);
           })
           $('body').on('click', '.post-dislike', function(e) {

               if($(this).find($(".fa")).hasClass('fa-thumbs-down'))
               {
                   $(this).find($(".fa")).removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down');
               }
               else if($(this).find($(".fa")).hasClass('fa-thumbs-o-down'))
               {
                   $(this).find($(".fa")).removeClass('fa-thumbs-o-down').addClass('fa-thumbs-down');
               }
               var post_id = $(this).data("value");
               var like = false;
               ajaxForLike(post_id,like);
           })
           if ($('.page').length > 0) {
               $('.loading-img').show();
               $.ajax({
                   url: username,
                   type: "post",
                   data: {
                       'date': $(".page:first").data("value"),
                       'userID' : userID,
                       '_token':$('meta[name=csrf-token]').attr("content"),
                   },
                   success: function(response){
                       $('.loading-img').hide();
                       $('.content').html(response);
                   }
               });
           }

           $('body').on('click', '.post_comment', function(e) {
               e.preventDefault(e);
               var post_id = $(this).attr("data");
               $.ajax({
                   url: username+'/storecomment',
                   type: "post",
                   data: {
                       'post_id':post_id,
                       'comment':$("#comment_"+post_id).val(),
                       '_token': $('meta[name=csrf-token]').attr("content")
                   },
                   success: function(response){
                       $('#comment_container_'+post_id).append(response);
                       $("#comment_"+post_id).val('');
                   }
               });
           });

           $('#postform').on('submit',function(e){
               $('.loading-img').show();
               e.preventDefault(e);

               $.embedly.oembed( $('input[name=content_url]').val() , {
                   key: 'b3dcecf7f6f34539a1c30746f3a64291',
               }).progress(function(data){
                   //PROGRESS COMMENT
               }).done(function(results){
                   $.each(results, function(i, data){
                       var media_url = null;
                       switch(data.type){
                           case 'link' :
                               media_url = (( data.thumbnail_url ) ? '<img src="'+data.thumbnail_url+'"  height="'+data.thumbnail_height+'" width="'+data.thumbnail_width+'" />' : null);
                               break;
                           case 'video' :
                               if(data.html) media_url = data.html;
                               else if (data.thumbnail_url)media_url = '<img src="'+data.thumbnail_url+'"  height="'+data.thumbnail_height+'" width="'+data.thumbnail_width+'" />';
                               else media_url = null;
                               break;
                           case 'photo' :
                               media_url = (( data.original_url ) ? '<img src="'+data.thumbnail_url+'"  height="'+data.thumbnail_height+'" width="'+data.thumbnail_width+'" />' : null);
                               break;
                           case 'rich':
                               media_url = data.html;
                               break;
                           default:
                               break;
                       }
                       var title = (( data.title ) ? data.title : data.original_url );
                       $.ajax({
                           url: username+'/storepost',
                           type: "post",
                           data: {
                               'content_url':$('input[name=content_url]').val(),
                               'media_url': media_url,
                               'type' : data.type,
                               'title':title,
                               'user_id':$('input[name=user_id]').val(),
                               '_token': $('meta[name=csrf-token]').attr("content"),
                           },
                           success: function(response){
                               $('.loading-img').hide();
                               $('.content').prepend(response);
                               $("#postbox").val('');
                           }
                       });

                   });
               });
           });

       })
   </script>
@endsection