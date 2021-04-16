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
			<b style="color: red">* Tidak Boleh Kosong</b>
			<div id="info"></div>
			<form id="insert" method="post" data-route="{{ route('insert.sekolah') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tingkat Pendidikan <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Tingkat Pendidikan"  name="tk_skl" id="tk_skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($tingkat_pendidikan as $tk)
										<option value="{{ encrypt_url($tk->tkpdId)}}">{{ $tk->tkpdNama.' '.$tk->tkpdStatus}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Status Kepemilikan <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Status Kepemilikan"  name="stk_skl" id="stk_skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($yayasan as $yas)
										<option value="{{ encrypt_url($yas->msysId)}}">{{ $yas->msysNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NPSN Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input required type="text" name="npsn_skl" class="form-control" placeholder="NPSN Sekolah">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">NIS Sekolah</label>
								<div class="col-lg-9">
									<input type="text" name="nis_skl" class="form-control" placeholder="NIS Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">ID Dapodik Sekolah</label>
								<div class="col-lg-9">
									<input type="text" name="dapo_skl" class="form-control" placeholder="ID Dapodik Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kode Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input required type="text" name="kode_skl" class="form-control" placeholder="Kode Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Sekolah <b style="color: red">*</b></label>
								<div class="col-lg-9">
									<input required type="text" name="nama_skl" class="form-control" placeholder="Nama Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Email Sekolah</label>
								<div class="col-lg-9">
									<input  type="email" name="email_skl" class="form-control" placeholder="Email Sekolah" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Alamat Sekolah</label>
								<div class="col-lg-9">
									<textarea class="form-control" name="alamat_skl" placeholder="Alamat Sekolah"></textarea>
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.sekolah') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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

