<div class="form-group">
    {!! Form::label('comment', trans('locale.postyourcomment')) !!}
    {!! Form::text('comment',null,['class' => 'form-control',"id"=>"comment_{$post_id}"]) !!}
</div>

<submitfield>
    {!! Form::button($submitButtonText,['class' => 'btn btn-primary form-control post_comment',"data"=>$post_id]) !!}
</submitfield>