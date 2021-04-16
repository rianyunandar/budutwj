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
			<form id="insert" method="post" data-route="{{ route('update.akun.admin') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-8">
						<input type="hidden" value="{{ $id }}" name="id" id="id" />
						<fieldset>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pilih Guru</label>
								<div class="col-lg-9">
									<select  data-placeholder="Pilih Guru"  name="guru" id="guru"  class="form-control select-search" data-fouc>
									<option></option>
									@foreach ($getGuru as $data)
										<option {{ selectAktif($dataAdmin->admUsername,$data->ugrUsername) }} value="{{ encrypt_url($data->ugrId) }}">{{ $data->ugrFirstName.' '.$data->ugrLastName}}</option>
									@endforeach
									</select>
								</div>
							</div>
							{{-- <div class="form-group row">
								<label class="col-lg-3 col-form-label">Username</label>
								<div class="col-lg-9">
									<input  type="text" name="username" class="form-control" placeholder="Masukan Username Jika Tidak Memilih Guru">
								</div>
							</div> --}}
							{{-- <div class="form-group row">
								<label class="col-lg-3 col-form-label">Password</label>
								<div class="col-lg-9">
									<input required type="text" name="password" class="form-control" placeholder="Password">
								</div>
							</div> --}}
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jabatan Hak Akses</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Jabatan Hak Akses"  name="hak" id="hak"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getJabatan as $data)
										<option {{ selectAktif($dataAdmin->admRole,$data->mjbKode) }}  value="{{ $data->mjbKode}}">{{ $data->mjbNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Sekolah</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									<option value="all">ALL</option>
									@foreach ($getSekolah as $skl)
										<option {{ selectAktif($dataAdmin->admSklId,$skl->sklId) }} value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>

							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary ">Simpan <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('list.akun.admin') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
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

