<?php if(Auth::guard('guru')->check()){ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="{{ env('AKADEMIK_KEYWORDS') }}" />
	<meta name="description" content="{{ env('AKADEMIK_DESKRIPSI') }}" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title}}</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" rel="icon" type="image/gif/png" href="{{ asset('image/budut.png') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/material/icons.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href="{{ asset('global_assets/js/plugins/datetimepicker/jquery.datetimepicker.css')}}" />
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/ripple.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/sticky.min.js')}}"></script>
	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

	<!-- /core JS files -->

	<!-- Theme JS files -->
	@stack('js_atas')
	<script src="{{ asset('assets/js/app.js')}}"></script>
	<style type="text/css">
		.color_mryes{
			background-color: #05405a; 
			color: white;
		}
		.center-table-mryes{
			text-align: center;
		}
		.loading {
	  position: absolute;
	  left: 50%;
	  top: 70%;
	  transform: translate(-50%,-50%);
	  font: 14px arial;
	  }
	  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('{{ asset('global_assets/images/ajax-loader.gif')}}') 50% 50% no-repeat rgb(249, 249, 249);
    opacity: .8;
}
	</style>
	<!-- /theme JS files -->
	
</head>

<body>
	<div id='pesan'></div>
	<div class='loader'>
		<div class="loading">
   	 <p id="pesanku" >Proses...</p>
  	</div>
	</div>

	<!-- Main navbar -->
	 @include('layout.guru.navbar')
	<!-- /main navbar -->

	<!-- Alternative navbar -->
	 {{-- @include('layout.alternativeNavbar') --}}
	<!-- /alternative navbar -->

	<!-- Page content -->
	<div class="page-content">
		<!-- Main sidebar -->
		<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">
			@include('layout.guru.menu')
		</div>
		<!-- /main sidebar -->
		<!-- Main content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
		<div class="footer">
			@include('layout.guru.footer')
		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->
	{{-- js --}}
	<script src="{{ asset('global_assets/js/demo_pages/navbar_multiple_sticky.js')}}"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.loader').fadeOut('slow');
	});
	</script>
	
	@stack('js_bawah')
	@stack('jsku')
</body>
</html>
<?php 
}
else{
	return view('login_user');
}
?>