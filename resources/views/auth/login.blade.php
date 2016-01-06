@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('locale.login') }}</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('locale.email') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('locale.password') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember">{{ trans('locale.rememberme') }}
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">{{ trans('locale.login') }}</button>

								{{--<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>--}}
								<a class="btn btn-link" href="{{ url('/') }}">{{ trans('locale.forgotpassword') }}</a>
							</div>
						</div>
					</form>
					<div class="dropdown form-group">
						<div class="col-md-6 col-md-offset-4">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ trans('locale.chooselanguage') }}
								<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="lang/en">{{ trans('locale.english') }}</a></li>
								<li><a href="lang/bn">{{ trans('locale.bangla') }}</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection