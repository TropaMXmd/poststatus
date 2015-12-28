<div class="form-group">
    {!! Form::label('comment','Post your comment ') !!}
    {!! Form::text('comment',null,['class' => 'form-control','id' => 'comment_'.$post->id]) !!}
</div>

<submitfield>
    <button class="submitbutton btn btn-primary form-control">Comment</button>
    {{--{!! Form::submit($submitButtonText,['class' => 'btn btn-primary form-control']) !!}--}}
</submitfield>