<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ config('app.name') }}</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="description" content="EM Dashboard">
	<meta name="author" content="Clinton Health Access Initiative">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

	<link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/themify-icons/themify-icons.min.css') }}">

	<link href="{{ asset('dashboard/css/nifty.min.css') }}" rel="stylesheet">
	<link href="{{ asset('dashboard/css/demo/nifty-demo-icons.min.css') }}" rel="stylesheet">

	<link href="{{ asset('dashboard/plugins/pace/pace.min.css') }}" rel="stylesheet">
	<script src="{{ asset('dashboard/plugins/pace/pace.min.js') }}"></script>

	<link href="{{ asset('dashboard/css/demo/nifty-demo-icons.min.css') }}" rel="stylesheet">

	<style type="text/css">
		html{
			scroll-behavior: smooth;
		}
	</style>


	@yield('css')
</head>
<body>
	<div id="app">
	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
	<div id="container" class="effect aside-float aside-bright mainnav-lg">
		<header id="navbar">
			<div id="navbar-container" class="boxed">
				<div class="navbar-header">
					<a class="navbar-brand" href="/">
						{{-- <img src="{{ asset('tumanisha-bird.png') }}" alt="{{ config('app.name') }} Logo" class="brand-icon"> --}}
						<div class="brand-title">
							<span class="brand-text">{{ config('app.name') }}</span>
						</div>
					</a>
				</div>


				<div class="navbar-content">
					<ul class="nav navbar-top-links">
						<li class="tgl-menu-btn">
							<a class="mainnav-toggle" href="#">
								<i class="demo-pli-list-view"></i>
							</a>
						</li>

						<li>
							<div class="custom-search-form">
								<label class="btn btn-trans" for="search-input" data-toggle="collapse" data-target="#nav-searchbox">
									<i class="demo-pli-magnifi-glass"></i>
								</label>
								<form>
									<div class="search-container collapse" id="nav-searchbox">
										<input id="search-input" type="text" class="form-control" placeholder="Type for search...">
									</div>
								</form>
							</div>
						</li>
					</ul>

					<ul class="nav navbar-top-links">
						<li>
							<a href="#">
								<i class="ti-upload"></i>
							</a>
                        </li>
						<li id="dropdown-user" class="dropdown">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
								<i class="demo-pli-user"></i>
								<span class="ic-user pull-right">
									<i class="demo-pli-male"></i>
								</span>
							</a>

							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
								<ul class="head-list">
									<li>
										<a href="#"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
									</li>
									<li>
										<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="demo-pli-unlock icon-lg icon-fw"></i> Log Out</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</header>

		<div class="boxed">
			<div id="content-container">
				<div id="page-head">
					@yield('custom_head')
					@if (trim($__env->yieldContent('title')))
					<div id="page-title">
						<h1 class="page-header text-overflow">@yield('title')</h1>
					</div>
					@endif
				</div>

				<div id="page-content">
					@if ($errors->any())
					<div class="alert alert-danger">
						<p>There was an error while adding your asset</p>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					@yield('content')
					
				</div>
			</div>

			<nav id="mainnav-container">
				<div id="mainnav">
					<div id="mainnav-menu-wrap">
						<div class="nano">
							<div class="nano-content">
								<div id="mainnav-profile" class="mainnav-profile">
									<div class="profile-wrap text-center">
										<div class="pad-btm">
											<img class="img-circle img-md" src="{{ asset('dashboard/img/profile-photos/1.png') }}" alt="Profile Picture">
										</div>
										<a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
											<span class="pull-right dropdown-toggle">
												<i class="dropdown-caret"></i>
											</span>
											<p class="mnp-name">{{ Auth::user()->name }}</p>
											<span class="mnp-desc">{{ Auth::user()->email }}</span>
										</a>
									</div>

									<div id="profile-nav" class="collapse list-group bg-trans">
										<a href="#" class="list-group-item">
											<i class="demo-pli-male icon-lg icon-fw"></i> View Profile
										</a>
										<a href="#" class="list-group-item">
											<i class="demo-pli-unlock icon-lg icon-fw"></i> Logout
										</a>
									</div>
								</div>

								<ul id="mainnav-menu" class="list-group">
									<li class="list-header">Navigation</li>
									<li>
										<a href="/">
											<i class="fa fa-flag-o"></i>
											<span class="menu-title">National Dashboard</span>
											<i class="arrow"></i>
										</a>
									</li>

									<li>
										<a href="{{ route('county-dashboard') }}">
											<i class="fa fa-sitemap"></i>
											<span class="menu-title">County Dashboard</span>
											<i class="arrow"></i>
										</a>
									</li>

									<li>
										<a href="{{ route('upload-page') }}">
											<i class="ti-upload"></i>
											<span class="menu-title">Upload Data</span>
											<i class="arrow"></i>
										</a>
									</li>

									<li>
										<a href="{{ route('upload-page') }}">
											<i class="ti-upload"></i>
											<span class="menu-title">Counties</span>
											<i class="arrow"></i>
										</a>
									</li>

									<li>
										<a href="{{ route('upload-page') }}">
											<i class=" fa fa-hospital-o"></i>
											<span class="menu-title">Facilities</span>
											<i class="arrow"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>

		<footer id="footer">
			
			<p class="pad-lft">&#0169; <?= @date('Y'); ?> EM Dashboard</p>
		</footer>

		<button class="scroll-top btn">
			<i class="pci-chevron chevron-up"></i>
		</button>

	</div>
	</div>
	@yield('modal')
	<!-- Javascript -->
	<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/js/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>	
	@yield('js')
	<script type="text/javascript" src="{{ asset('dashboard/js/nifty.min.js') }}"></script>

	<!-- <script type="text/javascript" src="{{ asset('dashboard/js/demo/nifty-demo.min.js') }}"></script> -->

</body>
</html>
