@if($comment)
<article> <b>{{ ucfirst($comment->user->username) }}</b> : {{ $comment->comment }}</article>
<hr/>
@endif