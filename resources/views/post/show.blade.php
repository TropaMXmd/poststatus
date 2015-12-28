@extends('app')

@section('content')

    <h1 style="color:green;">
        {{ ucfirst ($username) }}'s Profile
    </h1>
    </br>
    @if ( Auth::guest() == false && Auth::user()->id == $userid )
        <hr/>

        {!! Form::open(array('url'=> $username ,'method'=>'POST', 'id'=>'postform')) !!}
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
    @if($contentList)
      <img class="loading-img" src="/images/loading.gif" style="display:none;"></img>
      <div class="content-body">
      @include('partials.post',compact('content','comments'))
      </div>
       @if ($pageNo >0 && count($contentList)>1 )
           <div class="button">
               <a class="pull-left" href="/tropa/{{ $pageNo-1 }}">prev</a>
           </div>&nbsp;&nbsp;&nbsp;
       @endif
       @if ($pageNo < ($totalDateCount-1) && count($contentList)>1)
           <div><a class="pull-left" href="/{{ $username }}/{{ $pageNo+1 }}">next</div>
       @endif
   @endif
@endsection
@section('footerjs')
   <script>
       var username = "{{$username}}";
       $(document).ready(function(){
           $('#postform').on('submit',function(e){
              $('.loading-img').show();
               e.preventDefault(e);

               $.embedly.oembed( $('input[name=content_url]').val() , {
                   key: 'b3dcecf7f6f34539a1c30746f3a64291',
               }).progress(function(data){
                   // Called after each URL has been returned from the Embedly server. Order
                   // is not preserved for this method, so for long lists where URLs need to
                   // be batched the data results will likely be out of order.
                   //console.log(data.url, data.title);
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
                           url: username,
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
                              $('.content-body').prepend(response);
                           }
                       });

                   });
               });
           })
       });

   </script>
@endsection