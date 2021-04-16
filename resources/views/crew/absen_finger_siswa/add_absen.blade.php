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
			<form id="insert" method="post" data-route="{{ route('insert.absen.finger') }}">
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
										<option value="{{ encrypt_url($skl->sklId)}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jurusan</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Rombel</label>
								<div class="col-lg-5">
									<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Siswa</label>
								<div class="col-lg-7">
									<select required data-placeholder="Pilih Siswa"  name="siswa" id="siswa"  class="form-control select-search" data-fouc>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tanggal</label>
								<div class="col-lg-5">
									<input readonly required type="text" name="tgl" class="form-control daterange-single" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Status Kehadiran</label>
								<div class="col-lg-5">
									<select required data-placeholder="Pilih Status"  name="status" id="status"  class="form-control select-search" data-fouc>
									<option></option>
									@foreach ($getKategoriAbsen as $ka)
										<option value="{{ encrypt_url($ka->akKode)}}">{{ $ka->akNama}}</option>
									@endforeach
									</select>
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

{{-- Load Datetime --}}
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
@endpush
@push('jsku')
<script type="text/javascript">
	$(document).ready(function(){

		$('#skl').change(function(){
			var id=$(this).val();
			$('#jrs').empty();
			$('#rbl').empty();
			$('#siswa').empty();
			//alert(id);
			$.ajax({
				url : "{{ route('pilih.jurusan') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Jurusan</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idjrs+'>'+data[i].slugjrs+'</option>';
					}
					$('#jrs').html(html);
				}
			});
			return false;
		});
		$('#jrs').change(function(){
			var id=$(this).val();
			$('#rbl').empty();
			$('#siswa').empty();
			$.ajax({
				url : "{{ route('pilih.rombel') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Rombel</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idrbl+'>'+data[i].level+''+data[i].nmrbl+'</option>';
					}
					$('#rbl').html(html);
				}
			});
			return false;
		});
		$('#rbl').change(function(){
			var id=$(this).val();
			$('#siswa').empty();
			$.ajax({
				url : "{{ route('pilih.siswa') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Siswa</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idsiswa+'>'+data[i].namasiswa+'</option>';
					}

					$('#siswa').html(html);
				}
			});
			return false;
		});

		$('#insert').submit(function(e){
				var route = $(this).data('route');
				var data_form = $(this);
				e.preventDefault();
				$.ajax({
					type:'POST',
					url:route,
					data:data_form.serialize(),
					success:function(respon){
						//console.log(respon);
						if(respon.save){
							new Noty({
							theme: ' alert alert-success alert-styled-left p-0 bg-white',
							text: respon.save,
							type: 'success',
							progressBar: false,
							closeWith: ['button']
						}).show();
							setInterval(function(){ location.reload(true); }, 500);

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

	}); 
</script>
@endpush

