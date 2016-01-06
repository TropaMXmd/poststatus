{!! Form::hidden('user_id', Auth::user()->id ) !!}

<div class="form-group">
    {!! Form::label('status', trans('locale.whatsonyourmind') ) !!}
    {!! Form::text('content_url',null,['class' => 'form-control',"id"=>"postbox"]) !!}
</div>

<submitfield>
    {!! Form::submit($submitButtonText,['class' => 'btn btn-primary form-control']) !!}
</submitfield>