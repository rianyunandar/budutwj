@extends('master')
@section('titile', 'Tambah Siswa')
@section('content')
<!-- Content area -->
<div class="content">
	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">{{ $label}}</h5>
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
			<form id="addsiswa" method="post" data-route="{{ route('insert.alumni') }}">
				{{ csrf_field() }}
				<label>*Pastika sudah yakin dan benar, akan menjadikan siswa aktif menjadi alumni</label><br>
				<label>Jika sudah menjadi Alumni tidak bisa di kembalikan lagi menjadi siswa aktif</label>
				<div class="row">
					
					<div class="col-md-6">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Tahun Angkatan</th>
									<th>Jumlah Siswa</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($jm_angkatan as $data)
									@foreach ($data as $i => $value)
									<tr>
										<td>{{ $i }}</td>
										<td>{{ $value }}</td>
									</tr>
									@endforeach
								@endforeach
							</tbody>
							
						</table>
					</div>
					
					<div class="col-md-6">
						<br>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Tahun Angkatan</label>
							<div class="col-lg-9">
								<select required data-placeholder="Pilih Tahun Angkatan"  name="thn" id="thn"  class="form-control select-fixed-single" data-fouc>
								<option></option>
								@foreach ($angkatan as $an)
									<option value="{{ $an->ssaTahunAngkata}}">{{ $an->ssaTahunAngkata}}</option>
								@endforeach
								</select>
							</div>
						</div>
						
					</div>

				</div>
				@if(AksiInsert())
				<div class="text-right">
					<button id="start"   type="submit" class="btn btn-primary ">Jadikan Alumni<i class="icon-paperplane ml-2"></i></button>
					<button id="proses"  style="display: none;" class="btn bg-dark ">Proses...  <i class="icon-spinner9 spinner ml-2"></i></button>
					{{-- <button type="button" class="btn btn-light" id="notifikasi">Launch <i class="icon-play3 ml-2"></i></button> --}}
				</div>
				@endif
			</form>
			<hr>
			<div class="col-md-6">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Tahun Angkatan Alumni</th>
							<th>Jumlah Siswa Alumni</th>
						</tr>
					</thead>
					<tbody>
					
						@foreach ($jm_alumni as $data)
							@foreach ($data as $i => $value)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $value }}</td>
							</tr>
							@endforeach
						@endforeach
					</tbody>
					
				</table>
			</div>
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
	$(document).ready(function(){
		$('#jenis_smp').change(function(){
			var id=$(this).val();
			var token = $("meta[name='csrf-token']").attr("content");
			//alert(id);
			$.ajax({
				url : "{{ route('pilih.smp') }}",
				method : "POST",
				data : {_token: token,id: id},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].smpId+'>'+data[i].smpNama+'</option>';
					}
					$('#asal_smp').html(html);
					
					

				}

			});
			return false;

		});
	}); 
	$('#addsiswa').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();

			//console.log(route);
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				cache: false,
				beforeSend: function() {
					// $("#pesanku").text("Proses....");
					// $('.loader').show();
					$("#start").css("display", "none");
					$("#proses").css("display","");
				},
				success:function(respon){
					console.log(respon);
					if(respon.success){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success+' Total user : '+respon.user_berhasil+' profile : '+respon.profile_berhasil,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
				
					
				},
				complete: function (respon) {
					$("#start").css("display","");
					$("#proses").css("display", "none");
				},
				error: function (respon) {
					
					new Noty({
					theme: ' alert alert-danger alert-styled-left p-0',
					text: 'Error '+respon.status+' | '+respon.responseJSON.error,
					type: 'error',
					progressBar: false,
					closeWith: ['button']
					}).show();
				}

			});

		});	
</script>
@endpush

