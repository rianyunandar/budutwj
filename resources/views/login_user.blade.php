<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="title" content="SISTEM AKADEMIK SMK BUDI UTOMO"/>
	<meta name="description" content="SISTEM AKADEMIK SMK BUDI UTOMO">
	<meta name="keywords" content="SISTEM AKADEMIK SMK BUDI UTOMO">
	<meta name="url" content="budutwj.com">
	<title>{{env('AKADEMIK_NAMA_SEKOLAH')}}</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" rel="icon" type="image/gif/png" href="{{ asset('image/budut.png') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/extras/animate.min.css')}}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->
	<!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/ripple.min.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{ asset('assets/js/app.js')}}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/login.js')}}"></script>
	<!-- Select2 -->
	<script src="{{ asset('global_assets/js/demo_pages/form_checkboxes_radios.js')}}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
	
	<script type="text/javascript">
		var Select2Selects = function() {
    var _componentSelect2 = function() {
      $('.select').select2({ minimumResultsForSearch: Infinity });
    };

    return {
        init: function() {
            _componentSelect2();
        }
    }
}();


document.addEventListener('DOMContentLoaded', function() {
    Select2Selects.init();
});
	</script>
	<!-- /theme JS files -->

</head>

<body>
	{{-- style=" background-image: url('{{asset('global_assets/images/login_cover.jpg')}}'); --}}
	<!-- Page content -->
	<div class="page-content login-cover" style=" display: block; background: url('{{asset('image/bg.jpg')}}'); background-position: center;background-repeat: no-repeat; -webkit-background-size:cover;">


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login form -->
				<form class="login-form wmin-sm-400" method="POST" action="{{ route('ceklogin') }}">
					{{-- <div class="login-form wmin-sm-400"> --}}
						@csrf
						<div class="card mb-0">
							<div class="tab-content card-body">
								<div class="tab-pane fade show active" id="login-tab1">
									<div class="text-center mb-3">
										
										<img class="animate__animated animate__infinite animate__pulse " src="{{asset('image/budut.png')}}" style="max-height:100px" class="img-responsive" alt="Responsive image">
										<h5 class="mb-0">Selamat Datang</h5>
										<span class="d-block text-muted">Sistem Akademik Sekolah</span>
									</div>
									@if (session('error'))
									<div class="alert alert-danger">{{ session('error') }}</div>
									@endif
									@if (session('success'))
									<div class="alert alert-success">{{ session('success') }}</div>
									@endif
									<!-- Papan Informasi-->
									{{-- <div class="form-group">
										<div class="alert alert-primary alert-dismissable">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<br>
											<strong>Perhatian!</strong> sehubungan dengan Informasi <b>Pemberian bantuan kuota internet untuk mahasiswa</b> maka dari itu mahasiswa/i agar dapat mengecek <b>NIK (No KTP) , NO HP , NO TELPON &amp; EMAIL</b> apakah sudah sesuai atau tidak. 
										</div> 
									</div> --}}
									<!-- /Papan Informasi-->
									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="text" class="form-control" placeholder="Username" id="username" name="username">

										<div class="form-control-feedback">
											<i class="icon-user text-muted"></i>
										</div>
										@error('username')
										<span role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>

									<div class="form-group form-group-feedback form-group-feedback-left">
										<input type="password" class="form-control" placeholder="Password" id="password" name="password">
										<div class="form-control-feedback">
											<i class="icon-lock2 text-muted"></i>
										</div>
										@error('password')
										<span role="alert">
											<strong style="color: red">{{ $message }}</strong>
										</span>
										@enderror
									</div>
									
									<div class="form-group form-group-feedback form-group-feedback-left">
										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" name="remember" class="form-check-input-styled-primary" checked data-fouc>
												Ingitakan Saya
											</label>
										</div>
									</div>

									<div style="font-size: 15px;">Pilih Login Sebagai</div>
									<div class="form-group">
										<select required class="form-control select" data-fouc name="jabatan" id="jabatan">
										<optgroup label="Pilih Login Sebagai">
											<option value="SIS">Siswa</option>
											<option value="ORTU">Orang Tua / Wali</option>
											<option value="GURU">Guru</option>
										</optgroup>
									</select>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block" id="btnlogin">LOGIN</button>
									</div>

									
									{{-- <div class="form-group text-center text-muted content-divider">
										<span class="px-2">Jika Ada Masalah Bisa Klik WA</span>
									</div>

									<div class="form-group text-center">
										<button type="button" class="btn btn-outline bg-indigo border-indigo text-indigo btn-icon rounded-round border-2">Klik WA @mryes</button>
									</div> --}}
									<div class="form-group text-center text-muted content-divider">
										COPYRIGHT @ {{ env('AKADEMIK_COPYRIGHT') }}, {{env('AKADEMIK_NAMA_SEKOLAH')}} <br> 
										{{ env('AKADEMIK_VERSI') }} Created by <a href="https://web.facebook.com/youngkq?_rdc=1&_rdr" target="_blank">{{env('AKADEMIK_CREATEDBY')}}</a>
									</div>
								</div>
							</div>
						</div>
					{{-- </div> --}}
				</form>
				<!-- /login form -->
			</div>
			<!-- /content area -->
		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->
</body>
</html>

