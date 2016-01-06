<!doctype <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{csrf_token()}}"/>
	<title>Document</title>
	<link rel="stylesheet" href="/css/vendor/font-awesome/css/font-awesome.min.css" >
	<link rel="stylesheet" href="{{ elixir('output/final.css') }}" >
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				@if(Auth::user())
					<a class="navbar-brand" href="#">{{  ucfirst(Auth::user()->username) }}</a>
					@else
					<a class="navbar-brand" href="#">TestApp</a>
				@endif
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/') }}">{{ trans('locale.login') }}</a></li>
						<li><a href="{{ url('/register') }}">{{ trans('locale.signup') }}</a></li>
					@else
						<ul class="nav navbar-nav">
							<li><a style="color:red" href="{{ url('/auth/logout') }}">{{ trans('locale.logout') }}</a></li>
						</ul>
						{{--<li class="dropdown">--}}
							{{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>--}}
							{{--<ul class="dropdown-menu" role="menu">--}}
								{{--<li><a href="{{ url('/auth/logout') }}">Logout</a></li>--}}
							{{--</ul>--}}
						{{--</li>--}}
					@endif
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		@yield('content')
	</div>
	<script src="{{ elixir('output/scripts.js') }}" ></script>
	@yield('footerjs')
</body>
</html>