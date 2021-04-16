@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	
		<div class="row">
			<span id="result"></span>
			<div class="col-xl-3 col-sm-6">
				<div class="card">
					<div class="card-img-actions mx-1 mt-1" id="fotoprofile">
						<img class="card-img img-fluid " src="<?php echo GetFotoProfileSiswa().'?date='.time(); ?>" alt="{{ FullNamaSiswa() }}">
						<div class="card-img-actions-overlay card-img">
							<p>{{ FullNamaSiswa() }}</p>
						</div>
					</div>

					<div class="card-body text-center">
						<h6 class="font-weight-semibold mb-0">HAK AKSES</h6>
						<span class="d-block text-muted">Siswa</span><br>
						{{-- upload foto --}}
						@if(Setting()->setSiswaUploadFoto ==1)
						<span class="text-muted">Upload Foto Profile</span><br>

						<form id="upload" data-route="{{ route('upload.foto.siswa') }}" enctype="multipart/form-data" >
							<input type="hidden" name="id" value="{{ getIdSiswa() }}">
							<input required name="foto_upload" id="foto_upload" type="file" class="file-input" data-show-preview="false" data-show-remove="false" data-show-upload="false">
							<div class="text-right"><br>
									<button type="submit" class="btn btn-info"><i class="icon-paperplane"></i> Upload</button>
								</div>
						</form>
						@endif
						
						{{-- <ul class="list-inline list-inline-condensed mt-3 mb-0">
							<li class="list-inline-item"><a href="#" class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round">
								<i class="icon-google-drive"></i></a>
							</li>
							<li class="list-inline-item"><a href="#" class="btn btn-outline bg-info btn-icon text-info border-info border-2 rounded-round">
								<i class="icon-twitter"></i></a>
							</li>
							<li class="list-inline-item"><a href="#" class="btn btn-outline bg-grey-800 btn-icon text-grey-800 border-grey-800 border-2 rounded-round">
								<i class="icon-github"></i></a>
							</li>
						</ul> --}}
					</div>
				</div>
			</div>
		
		</div>
		<br>
		<br>

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


</script>
@endpush

