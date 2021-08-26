@extends('master_guru')
@section('title')
@section('content')
<?php if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; $aktif1="active"; $aktif2=""; } else{ $getbln =''; $aktif1=""; $aktif2="active";} 
if(!empty($_GET['bln'])){ 
	$url = 'guru/json-log-absen-guru-sekolah?bln='.$getbln; 
} else{ $url='guru/json-log-absen-guru-sekolah';}

$jamMasuk= strtotime(getJamMasukSekolah());
$jamTerlambat = strtotime(getJamTerlamabtSekolah());
$jamNow = strtotime(date("H:i"));
?>
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-md-12">
			
			<div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title">{!! $label !!}</h6>
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
					</div>
				</div>

				<div class="card-body">
					{{-- data anggota kelas --}}
					<div class="row">
						<div class="col-md-12">
							<!-- Tabs widget -->
							<ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
								<li class="nav-item"><a href="#tab-desc" class="nav-link {{ $aktif2 }}" data-toggle="tab">Absensi Masuk</a></li>
								<li class="nav-item"><a href="#tab-spec" class="nav-link {{ $aktif1 }}" data-toggle="tab">Log Absensi</a></li>
							</ul>

							<div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
								<div class="tab-pane fade show {{ $aktif2 }}" id="tab-desc">
									<div class="col-md-12 text-center">
										<i class="icon-reading icon-2x text-blue border-blue border-3 rounded-round p-3 mb-3 mt-1"></i>
										<h5 class="card-title">Absensi Kehadiran GURU</h5>
										<p class="mb-3">Silahkan Klik Tombol di Bawah ini
											Untuk Melakukan Absen</p>
										{{-- <p class="mb-3">Jam Mulai Absen {{ getJamMasukSekolah() }} <br> Batas Absen Masuk Jam {{ getJamTerlamabtSekolah() }}</p> --}}
										<button data-id="1" class="absensekolah btn bg-blue">ABSEN MASUK</button>
										<button data-id="2" class="absensekolah btn bg-warning">ABSEN PULANG</button>

									</div>
								</div>
							
								<div class="tab-pane fade show {{ $aktif1 }}" id="tab-spec">
									<p class="mb-3">Silahkan Pilih Bulan Terlebih Dahulu</p>
									<div class="row mb-3">
										<div class="col-lg-3">
												<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select-search" data-fouc>
													<option></option>
													@foreach (getBulan() as $data )
													<option   {{ selectAktif($getbln, encrypt_url($data)) }} value="{{ encrypt_url($data) }}">{{ bulanIndo($data) }}</option>
												@endforeach
												</select>
										</div>
									</div>
									@if (!empty($getbln))
									<div class="table-responsive">
										<table id="akunsiswa" class="table table-striped table-bordered " width="100" style="width: 100%">
											<thead style="background-color: #05405a; color: white;">
												<tr>
													<th>USERNAME</th>
													<th>NAMA</th>
													<th>HARI</th>
													<th>TANGGAL</th>
													<th>JAM MASUK</th>
													<th>JAM PULANG</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
									@endif
								</div>
							</div>
						<!-- /tabs widget -->
						</div>
					</div>
				</div>
			</div>
			<!-- /directory -->
		</div>
	</div>
</div>
<br><br>
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
@push('js_bawah')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>


<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
$(document).ready(function(){
	$('.select-search').select2();
	$('#bln').change(function(){
			var bln = $(this).val();
			location="?bln="+bln;
	});

	var tabel_siswa = $('#akunsiswa').DataTable({
			language: {
				search: '<span>Cari:</span> _INPUT_',
				searchPlaceholder: 'Ketik di sini',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			processing: true,
			//serverSide: true,
			responsive: true,
			ajax: '{{ url($url) }}',
			columns: [
				{ "data": "hgUserGuru" },
				{ "data": "hgNamaFull" },
				{ "data": "hari" },
				{ "data": "tanggal" },
				{ "data": "hgJamIn" },
				{ "data": "hgJamOut" },
				
			],

	});


	$('.absensekolah').click(function(){
		var idabsen = $(this).data('id');
		$.ajax({
			type:'POST',
			url:"{{ route('insert.absen.kehadiran.guru') }}",
			data:{_token: "{{ csrf_token() }}", idabsen:idabsen},
			success:function(respon){
				console.log(respon);
				if(respon.success){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					//setInterval(function(){ location.reload(true); }, 1000);
				}
				if(respon.warning){
						new Noty({
						theme: ' alert alert-warning alert-styled-left p-0',
						text: respon.warning,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
				}

				},
				error: function (respon) {
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
				 }

		});

	});
});
</script>
@endpush

