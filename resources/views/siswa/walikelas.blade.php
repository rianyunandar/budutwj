@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
		@if(!empty($wakas))
		<div class="row">
			<span id="result"></span>
			<div class="col-xl-3 col-sm-6">
				<div class="card">
					<div class="card-img-actions mx-1 mt-1" id="fotoprofile">
						<img class="card-img img-fluid " src="<?php echo GetFotoProfile('{{ $wakas->user_guru->ugrFotoProfile }}').'?date='.time(); ?>" alt="{{ $wakas->user_guru->ugrFirstName }}">
					</div>

					<div class="card-body text-center">
						<h6 class="font-weight-semibold mb-0">HAK AKSES</h6>
						<span class="d-block text-muted">WALI KELAS</span><br>
					</div>
				</div>
			</div>
			<div class="col-xl-9 col-sm-6">
				<!-- Account settings -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title"><b>{{ $label }}</b></h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
								<a class="list-icons-item" data-action="reload"></a>
								<a class="list-icons-item" data-action="remove"></a>
							</div>
						</div>
					</div>
					
					<div class="card-body">
						
							<div class="form-group">
								<div class="row mb-3">
									<div class="col-md-6">
										<label>Nama Depan</label>
										<input disabled name="namadepan" required value="{{ $wakas->user_guru->ugrFirstName }}" type="text" class="form-control">
									</div>

									<div class="col-md-6">
										<label>Nama Belakang</label>
										<input disabled name="namabelakang" required type="text" value="{{ $wakas->user_guru->ugrLastName }}" class="form-control">
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-md-6">
										<label>NO HP</label>
										<input disabled name="namadepan" required value="{{ $wakas->user_guru->ugrHp }}" type="text" class="form-control">
									</div>

									<div class="col-md-6">
										<label>NO WA</label>
										<input disabled name="namabelakang" required type="text" value="{{ $wakas->user_guru->ugrWa }}" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-md-6">
										<label>Tempat Lahir</label>
										<input disabled name="namadepan" required value="{{ $wakas->user_guru->profile_guru->prgTpl }}" type="text" class="form-control">
									</div>

									<div class="col-md-6">
										<label>Tanggal Lahir</label>
										<?php $tgl = date('d-M-Y',strtotime($wakas->user_guru->profile_guru->prgTgl)); ?>
										<input disabled name="namabelakang" required type="text" value="{{ $tgl }}" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-md-6">
										<label>Alamat</label>
										<input disabled name="namadepan" required value="{{ $wakas->user_guru->profile_guru->prgAlamat }}" type="text" class="form-control">
									</div>
								</div>

							</div>
							<hr>
					</div>
				
				</div>
				<!-- /account settings -->
			</div>
		</div>
		@else
		<div class="row">
			<span id="result"></span>
			<div class="col-xl-9 col-sm-6">
				<!-- Account settings -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title"><b>{{ $label }}</b></h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
								<a class="list-icons-item" data-action="reload"></a>
								<a class="list-icons-item" data-action="remove"></a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="content d-flex justify-content-center align-items-center">
							<div class="flex-fill">
								<!-- Error title -->
									<div class="text-center mb-3">
										<h1 class="error-title offline-title">Upsss</h1>
										<h5>{!! $ups !!}</h5>
									</div>
								<!-- /error title -->
							</div>
						</div>
					</div>
				</div>
				<!-- /account settings -->
			</div>
		</div>
		@endif
	

</div>
<!-- /content area -->
@endsection
@push('js_atas')

<!-- pluginnya datatables-->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

<!-- pluginnya form select-->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<!-- pluginnya buat export -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

{{-- Upload --}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
	// {{-- Input upload --}}
	$('.file-input').fileinput({
		browseLabel: 'Cari',
		browseIcon: '<i class="icon-file-plus mr-2"></i>',
		//uploadIcon: '<i class="icon-file-upload2 "></i>',
		//removeIcon: '<i class="icon-cross2 font-size-base"></i>',
		initialCaption: "Tidak Ada File",
	});
	$('#upload').submit(function(e){
		var route = $(this).data('route');
		var data = new FormData(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data:data,
			processData: false,  // Important!
      contentType: false,
      cache: false,
      beforeSend: function() {
      	$("#pesanku").text("Proses Upload ...!");
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
					//setInterval(function(){ location.reload(true); }, 1000);
					document.getElementById("foto_upload").form.reset();
					$("#fotoprofile").load(location.href + " #fotoprofile");
					$("#fotoprofilemenu").load(location.href + " #fotoprofilemenu");
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
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}

		});
	});	
	$('#form').submit(function(e){
		var route = $(this).data('route');
		var data_form = $(this);
		e.preventDefault();
		$.ajax({
			type:'PUT',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data:data_form.serialize(),
			success:function(respon){
				console.log(respon);
				if(respon.save){
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					setInterval(function(){ location.reload(true); }, 1000);
				}
				if(respon.error){
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
				}
			}

		});
	});	

</script>
@endpush

