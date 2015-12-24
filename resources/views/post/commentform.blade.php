{!! Form::hidden('user_id', Auth::user()->id ) !!}
{!! Form::hidden('username', $username ) !!}
{!! Form::hidden('post_id', $content->id ) !!}
<div class="form-group">
    {!! Form::label('comment','Post your comment ') !!}
    {!! Form::text('comment',null,['class' => 'form-control']) !!}
</div>

<submitfield>
    {!! Form::submit($submitButtonText,['class' => 'btn btn-primary form-control']) !!}
</submitfield>