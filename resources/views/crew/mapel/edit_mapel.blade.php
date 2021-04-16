@extends('master')
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
			<form id="insert" method="post" data-route="{{ route('update.mapel') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<input type="hidden" name="id" id="id" value="{{ $id }}" >
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Sekolah</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									<option {{ selectAktif($dataMapel->mapelSklId,"all") }} value="all">ALL</option>
									@foreach ($getSekolah as $skl)
										<option {{ selectAktif($dataMapel->mapelSklId,$skl->sklId) }} value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kelas</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih Kelas"  name="kls" id="kls"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									<option {{ selectAktif($dataMapel->mapelKlsId,"all") }} value="all">ALL</option>
									@foreach ($getKelas as $kls)
										<option {{ selectAktif($dataMapel->mapelKlsId,$kls->klsId) }} value="{{ $kls->klsId}}">{{ $kls->klsNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jurusan</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									<option {{ selectAktif($dataMapel->mapelJrsId,"all") }} value="all">ALL</option>
									@foreach ($getJurusan as $jrs)
										<option {{ selectAktif($dataMapel->mapelJrsId,$jrs->jrsId) }} value="{{ $jrs->jrsId}}">{{ $jrs->jrsNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kategori</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Kategori"  name="kategori" id="kategori"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									<option {{ selectAktif($dataMapel->mapelMpktKode,"UMUM") }} value="UMUM">UMUM</option>
									<option {{ selectAktif($dataMapel->mapelMpktKode,"KEJURUAN") }} value="KEJURUAN">KEJURUAN</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kode Mapel</label>
								<div class="col-lg-9">
									<input value="{{ $dataMapel->mapelKode }}" required type="text" name="kodemapel" class="form-control" placeholder="Kode Mapel">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Slug Mapel</label>
								<div class="col-lg-9">
									<input value="{{ $dataMapel->mapelSlug }}" required type="text" name="slugmapel" class="form-control" placeholder="Slug Mapel">
									<span class="text-muted">Bisa di samakan dengan kode mapel</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Mapel</label>
								<div class="col-lg-9">
									<input value="{{ $dataMapel->mapelNama }}" required type="text" name="namamapel" class="form-control" placeholder="Nama Mapel">
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Paket Mapel</label>
								<div class="col-lg-9">
									<input value="{{ $dataMapel->mapelPaket }}" type="text" name="paketmapel" class="form-control" placeholder="Misal C.1 C.2 ">
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.mapel') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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
				type:'PUT',
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

