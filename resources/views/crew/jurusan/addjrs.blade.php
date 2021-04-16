@extends('master')
@section('titile', 'Tambah Jurusan')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- 2 columns form -->
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
			<div id="info"></div>
			<form id="insert" method="post" data-route="{{ route('insert.jurusan') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kode Jurusan</label>
								<div class="col-lg-9">
									<input required type="text" name="jrsKode" class="form-control" placeholder="Misal 160784 Bisa Lihat Di Dapodik">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Slag Jurusan</label>
								<div class="col-lg-9">
									<input required type="text" name="slagjrs" class="form-control" placeholder="Misal AK atau TBSM">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Jurusan</label>
								<div class="col-lg-9">
									<input required type="text" name="namajrs" class="form-control" placeholder="Nama Jurusan">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Sekolah</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getSekolah as $skl)
										<option value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>

							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihatJurusan') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
				</div>
					
			</form>
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
@endpush
@push('jsku')
<script type="text/javascript">
	$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'POST',
				url:route,
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

