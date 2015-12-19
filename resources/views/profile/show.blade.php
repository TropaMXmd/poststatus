@extends('app')

@section('content')

    <h1 style="color:green;">
        {{ ucfirst ($username) }}'s Profile
    </h1>
    </br>
    @if ( Auth::guest() == false && Auth::user()->id == $userid )
        <hr/>

        {!! Form::open(['url' => '/update' ]) !!}
            @include('profile.form',['submitButtonText' => 'Share Link'])
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

    @foreach( $contentList as $user)
        <article>
            <blockquote class="embedly-card">
                {!! $user->media_url  !!}
                <h4><a href="{{ $user->content_url }}">{{ $user->title }}</a></h4>
                    Posted on: {{ $user->created_at }}
            </blockquote>
        </article>
    @endforeach
    @if ($pageNo >0 && count($contentList)>1 )
        <div class="button">
            <a class="pull-left" href="/tropa/{{ $pageNo-1 }}">prev</a>
        </div>&nbsp;&nbsp;&nbsp; 
    @endif 
    @if ($pageNo < ($totalDateCount-1) && count($contentList)>1)
        <div><a class="pull-left" href="/{{ $username }}/{{ $pageNo+1 }}">next</div> 
    @endif

@stop