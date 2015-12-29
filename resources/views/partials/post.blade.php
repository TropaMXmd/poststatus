@if($post)
    <article>
        <blockquote class="embedly-card">
            {!! $post->media_url  !!}
            <h4><a href="{{ $post->content_url }}">{{ $post->title }}</a></h4>
            Posted on: {{ $post->created_at }}
        </blockquote>
    @if( Auth::check())
            <hr/>
        <a class="post-like" data-value="{{ $post->id }}"><i class="fa fa-thumbs-o-up"> Like</i> </a>
        <a class="post-dislike" data-value="{{ $post->id }}"><i class="fa fa-thumbs-o-down"> Dislike</i></a>
        <div class="comment-box" >
            <div class="showlikes">
                @if($post->Likes()->count() > 0)
                    <a>{{ $post->Likes()->count() }} Likes </a>
                @endif
                @if( $post->Dislikes()->count() > 0)
                    <a>, {{ $post->Dislikes()->count() }} Dislikes</a>
                @endif
            </div>
            <hr/>
            <div id="comment_container_{{$post->id}}">
                @if(count($post->comments) > 0)
                    @foreach($post->comments as $comment)
                        @if($comment->post_id == $post->id)
                            @include('partials.comment',["comment"=>$comment])
                        @endif
                    @endforeach
                @endif
            </div>

            {{--Comment form--}}
            @include('post.commentform',['submitButtonText' => 'Comment',"post_id"=>$post->id])
        </div>
        @endif
</article>
@endif
