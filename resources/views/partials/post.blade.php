@if($post)
    <article>
        <blockquote class="embedly-card">
            {!! $post->media_url  !!}
            <h4><a href="{{ $post->content_url }}">{{ $post->title }}</a></h4>
            Posted on: {{ $post->created_at }}
            <hr/>
            <p><i class="fa fa-thumbs-up"> Like</i> <i class="fa fa-thumbs-down"> Dislike</i></p>
        </blockquote>
        <div class="comment-box">
            @if(count($post->comments) > 0)
                @foreach($post->comments as $comment)
                    @if($comment->post_id == $post->id)
                        <article> <b>{{ ucfirst($comment->user->username) }}</b> : {{ $comment->comment }}</article>
                        <hr/>
                    @endif
                @endforeach
            @endif
            {{--Comment form--}}
            {!! Form::open(array('url'=> $name ,'method'=>'POST', 'id'=>'commentform')) !!}
                @include('post.commentform',['submitButtonText' => 'Comment'])
            {!! Form::close() !!}
        </div>
    </article>
@endif