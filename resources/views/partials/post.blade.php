<article>
    <blockquote class="embedly-card">
        {!! $content->media_url  !!}
        <h4><a href="{{ $content->content_url }}">{{ $content->title }}</a></h4>
        Posted on: {{ $content->created_at }}
        <hr/>
        <p><i class="fa fa-thumbs-up"> Like</i> <i class="fa fa-thumbs-down"> Dislike</i></p>
    </blockquote>
    <div class="comment-box">
        @foreach($comments as $comment)
            @foreach($comment as $data)
                @if($data->post_id == $content->id)
                    <article> <b>{{ ucfirst($data->user->username) }}</b> : {{ $data->comment }}</article>
                    <hr/>
                @endif
            @endforeach
        @endforeach
        {{--Comment form--}}
        {!! Form::open(['url' => 'postcomment' ]) !!}
        @include('post.commentform',['submitButtonText' => 'Comment'])
        {!! Form::close() !!}
    </div>
</article>