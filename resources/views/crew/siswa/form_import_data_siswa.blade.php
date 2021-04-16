@extends('master')
@section('titile', 'Tambah Jurusan')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- from import data siswa -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">{!!$label!!}</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<span id="result">
				
			</span>
			{{-- @if ($message = Session::get('save'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    	@endif --}}
    	 {{-- @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ $message }}</strong>
      </div>
	    @endif

	    @if ($message = Session::get('error'))
	      <div class="alert alert-warning alert-block">
	        <button type="button" class="close" data-dismiss="alert">×</button> 
	        <strong>{{ $message }}</strong>
	    </div>
	    @endif --}}

			<div class="row">
					<div class="col-md-8">Format File Excel Upload Data Siswa .xlsx{{-- <i class="icon-info22 ml-1" data-popup="tooltip" title="Guru Juga Bisa Login Untuk Mengisi atau Melengkapi Data Profile" data-placement="bottom"></i> --}}</div>
				</div>
				<hr class="mt-1 mb-1"/>
			<form id="upload"  data-route="{{ route('import.data.siswa') }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pilih File Excel </label>
								<div class="col-lg-9">
									<input required type="file" name="import_data_siswa" class="form-control" >
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Upload Data <i class="icon-upload4 ml-2"></i></button>
				</div>
				{{-- <div class="progress progress-striped active ">
					<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
						<span class="sr-only"></span>
					</div>
				</div> --}}
				
			</form>
		<span class="form-text text-muted">
			Catatan : Import data di gunakan untuk menambah data siswa secara masal <br>
			Pastikan data sudah benar dan fix sebelum di import
		</span>
		</div>
	</div>
	<!-- from import insert data siswa -->

	<!-- from import update data siswa -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title"><b>Update Data Siswa</b></h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body">
			@if ($message = Session::get('saveupdate'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    	@endif
    	 @if ($message = Session::get('errorrupdate'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>{{ $message }}</strong>
      </div>
	    @endif

	    @if ($message = Session::get('errorupdate'))
	      <div class="alert alert-warning alert-block">
	        <button type="button" class="close" data-dismiss="alert">×</button> 
	        <strong>{{ $message }}</strong>
	    </div>
	    @endif

			<div class="row">
					<div class="col-md-8">Format File Excel Upload Data Siswa .xlsx</div>
				</div>
				<hr class="mt-1 mb-1"/>
				<span><i style="color: red">Nama Kolom Key di gunakan sebagai kuni atau id siswa yang mau di update</i></span>
			<form action="{{ route('import.update.data.siswa') }}" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Kolom Key </label>
								<div class="col-lg-9">
									<select data-placeholder="USERNAME SISWA, NIS, NISN" required class="form-control select-fixed-single" name="nama_kolom_key" data-fouc>
										<option></option>
										<option value="psSsaUsername">Username</option>
										<option value="psNis">NIS</option>
										<option value="psNisn">NISN</option>
									</select>
									
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Kolom di Update </label>
								<div class="col-lg-9">
									{{-- <input required type="text" name="nama_kolom" class="form-control" placeholder="Masukan Nama Kolom pada Tabel Database"> --}}
									<select id="namakolo" name="nama_kolom" data-placeholder="Pilih Kolom Yang mau di Update" class="form-control form-control-select2 jenis_smp" data-fouc>
										
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pilih File Excel </label>
								<div class="col-lg-9">
									<input required type="file" name="import_update_data_siswa" class="form-control" >
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Upload Data <i class="icon-upload4 ml-2"></i></button>
				</div>
			</form>
		<span class="form-text text-muted">
			Catatan : <b>Nama Depan, Nama Belakang, Email, Barcode, Tahun Angkatan</b> hanya bisa di update menggunakan Username
		</span>
		</div>

	</div>
	<!-- /2 columns form -->

	<!-- /dashboard content -->
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<!-- pluginnya -->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

	$(document).ready(function(){
			$.ajax({
				url : "{{ route('pilih.kolom') }}",
				method : "GET",
				async : false,
				dataType : 'json',
				success: function(data){
					console.log(data);
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].value+'>'+data[i].nama+'</option>';
					}
					$('#namakolo').html(html);
				}
			});
			return false;
	}); 
	// $('#insert').submit(function(e){
	// 		var route = $(this).data('route');
	// 		var data_form = $(this);
	// 		e.preventDefault();
	// 		$.ajax({
	// 			type:'POST',
	// 			url:route,
	// 			data:data_form.serialize(),
	// 			success:function(respon){
	// 				console.log(respon);
	// 				if(respon.ugrUsername){
	// 					new Noty({
	// 					theme: 'alert alert-danger alert-styled-left p-0',
	// 					text: respon.ugrUsername,
	// 					type: 'error',
	// 					progressBar: false,
	// 					closeWith: ['button']
	// 					}).show();
	// 				}
	// 				if(respon.save){
	// 					new Noty({
	// 					theme: ' alert alert-success alert-styled-left p-0 bg-white',
	// 					text: respon.save,
	// 					type: 'success',
	// 					progressBar: false,
	// 					closeWith: ['button']
	// 				}).show();
	// 				setInterval(function(){ location.reload(true); }, 1000);
	// 				}
	// 				if(respon.error){
	// 					new Noty({
	// 					theme: ' alert alert-danger alert-styled-left p-0',
	// 					text: respon.error,
	// 					type: 'error',
	// 					progressBar: false,
	// 					closeWith: ['button']
	// 					}).show();
	// 				}
	// 			}

	// 		});

	// 	});	
	$('#upload').submit(function(e){
		var route = $(this).data('route');
		var data = new FormData(this);
		e.preventDefault();
		
		$.ajax({
			type:'POST',
			url:route,
			data:data,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			processData: false,  // Important!
      contentType: false,
      cache: false,
			//async: true,
      beforeSend: function() {
      	$("#pesanku").text("Proses Import ...!");
      	$('.loader').show();
      },
			success:function(respon){
				//console.log(respon);
				if(respon.save){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-success alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.save+'</strong></div>');
					//setInterval(function(){ location.reload(true); }, 1000);
				}
				if(respon.error){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-danger alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.error+'</strong></div>');
				}
				if(respon.danger){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-warning alert-styled-left p-0',
						text: respon.danger,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
					$("#result").append(' <div class="alert alert-warning alert-block">'
					+'<button type="button" class="close" data-dismiss="alert">×</button>'
					+'<strong>'+respon.danger+'</strong></div>');
				}
			},
			error: function (e) {
				$('.loader').hide();
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}

		});
	});
</script>

@endpush

