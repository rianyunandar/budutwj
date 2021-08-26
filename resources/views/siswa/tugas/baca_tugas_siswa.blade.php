
@extends('master_siswa')
@section('content')

<!-- Content area -->
<div class="content">

	<div class="row">
		<div class="col-md-12">
			{{-- <div class="card-header header-elements-inline">

			</div> --}}
			<div class="card">
				<div class="card-body">
					<div class="row pb-4">
						<div class="col-md-12">
							<a class="btn btn-dark" href="javascript:history.back()"><i class="icon-arrow-left52"></i> Pilih {{ $tombol }}</a>
						</div>
					</div>
					<div class="row pb-4">
						<div class="col-md-12">
							<h4 style="text-align: center" class="card-title"><b>{{ $getData->tugasJudul }}</b></h4>
						</div>
					</div>
					@if(!empty($getData->tugasPesanSingkat))
					<div class="row pb-4">
						<div class="col-md-12">
							<center>
							<label  class="text-muted" >Pesan Singkat Guru </label>
						</center>
							<textarea style="text-align: center" class="form-control" disabled >{!! $getData->tugasPesanSingkat !!}</textarea>
						</div>
					</div>
					@endif
					<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
						<li class="nav-item"><a href="#justified-right-icon-tab1" class="nav-link active" data-toggle="tab"><b>{{ $tombol }}</b></a></li>
						@if(!empty($getData->tugasFile))
							<li class="nav-item"><a href="#justified-right-icon-tab2" class="nav-link" data-toggle="tab"><b>Downlaod File </b></a></li>
						@endif
						
					</ul>

					<div class="tab-content">
						<div class="tab-pane fade show active" id="justified-right-icon-tab1">
							{{-- <textarea id="summernote-editor" name="isi">{{ $datamateri->materiIsi }}</textarea> --}}
							
						
							@if(!empty($getData->tugasIsi))
								{!! $getData->tugasIsi !!}
							@endif
							<br>
							@if(!empty($getData->tugasLink))
								<style>
									[style*="--aspect-ratio"] > :first-child {
										width: 100%;
									}
									[style*="--aspect-ratio"] > img {  
										height: auto;
									}
									@supports (--custom:property) {
										[style*="--aspect-ratio"] {
											position: relative;
										}
										[style*="--aspect-ratio"]::before {
											content: "";
											display: block;
											padding-bottom: calc(100% / (var(--aspect-ratio)));
										}  
										[style*="--aspect-ratio"] > :first-child {
											position: absolute;
											top: 0;
											left: 0;
											height: 100%;
										}  
									}
								</style>
									<div style="--aspect-ratio: 16/9;">
										<iframe
										class="embed-responsive-item" 
											src="{{ $getData->tugasLink }}" 
											width="1600"
											height="900"
											frameborder="0"
										>
										</iframe>
									</div>
							@endif
							
						</div>
						<div class="tab-pane" id="justified-right-icon-tab2">
							
							@if(!empty($getData->tugasFile))
							
							{{-- download file --}}
							<div class="tab-pane " id="justified-right-icon-tab2">
								<label class="text-muted" >Silahkan Klik untuk Mendownload Materinya</label><br>
								<a target="_blank" class="btn btn-primary" href="{{ $getData->tugasFile }}"><i class="icon-download"></i> Download Materi</a>
								<br><br>
							</div>
						
							@endif
						</div>
						
						

					</div>

					<div class="row pb-2 pt-2 border-top">
						<div class="col-md-12">
							<a class="btn btn-dark" href="javascript:history.back()"><i class="icon-arrow-left52"></i> Pilih {{ $tombol }}</a>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<br><br><br>

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

<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>

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
$('#summernote-editor').summernote({
	toolbar: []
});

	var tabel_siswa = $('#akunsiswa').DataTable({
			language: {
				search: '<span>Cari:</span> _INPUT_',
				searchPlaceholder: 'Ketikan disini...',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			
	});

</script>
@endpush

