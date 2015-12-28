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
            $('.loading-img').show();
            if ($('.page').length > 0) {
                $.ajax({
                    url: username,
                    type: "post",
                    data: {
                        'date': $(".page:first").data("value"),
                        'userID' : userID,
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(response){
                        $('.loading-img').hide();
                        $('.content').html(response);
                    }
                });
            }
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
                                '_token': $('input[name=_token]').val(),
                            },
                            success: function(response){
                                $('.loading-img').hide();
                                $('.content').prepend(response);
                            }
                        });

                    });
                });
            })

            $('#commentform').on('submit',function(e){
                $('.loading-img').show();
                e.preventDefault(e);

                $.ajax({
                    url: username+'/storecomment',
                    type: "post",
                    data: {
                        'post_id':$('input[name=post_id]').val(),
                        'user_id':$('input[name=user_id]').val(),
                        'comment':$('input[name=comment]').val(),
                        '_token': $('input[name=_token]').val(),
                    },
                    success: function(response){
                        $('.loading-img').hide();
                        //$('.comment-box').append(response);
                    }
                });
            })


            $(".page").click(function(){
                var date =$(this).data("value");

            });
        })
    </script>
@endsection