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
			<form id="insert" method="post" data-route="{{ route('insert.rombel') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
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
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kelas</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Kelas"  name="kls" id="kls"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getKelas as $kls)
										<option value="{{ $kls->klsId}}">{{ $kls->klsNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jurusan</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getJurusan as $jrs)
										<option value="{{ $jrs->jrsId}}">{{ $jrs->jrsNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kode Rombel</label>
								<div class="col-lg-9">
									<input required type="text" name="rblKode" class="form-control" placeholder="Misal 2021AKL1 atau 1920TBSM1">
									<span class="form-text text-muted">Kode Rombel Dari Tahun Angkatan dan Nama Rombel </span>
									<span class="form-text text-muted">(Misal 2021AKL1 atau 1920TBSM1) </span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Rombel</label>
								<div class="col-lg-9">
									<input required type="text" name="namarombel" class="form-control" placeholder="Misal AKL1 atau TBSM2">
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.rombel') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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
					if(respon.rblKode){
						new Noty({
						theme: 'alert alert-danger alert-styled-left p-0',
						text: respon.rblKode,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
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

