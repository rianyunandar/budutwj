@extends('master_siswa')
@section('title')
@section('content')
<?php if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; $aktif1="active"; $aktif2=""; } else{ $getbln =''; $aktif1=""; $aktif2="active";} 
if(!empty($_GET['bln'])){ 
	$url = 'siswa/json-total-absen-sekolah?bln='.$getbln; 
} else{ $url='siswa/json-total-absen-sekolah';}

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
							<div >
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
								<div class="row">
									<div class="col-md-12">
										<table id="tabel" class="table table-striped table-bordered  ">
											<thead style="background-color: #05405a; color: white;">
												<tr>
													<th></th>
													<th>USERNAME</th>
													<th>NAMA SISWA</th>
													<th>KELAS</th>
													<th>H</th>
													<th>I</th>
													<th>S</th>
													<th>A</th>
													<th>T</th>
													<th>B</th>
													<th>TOTAL</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
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
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
$(document).ready(function(){

	$('#bln').change(function(){
			var bln = $(this).val();
			location="?bln="+bln;
	});

	var tabel = $('#tabel').DataTable({
		processing: true,
		//serverSide: true,
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		autoWidth: false,
		ajax: '{{ url($url) }}',
		buttons: 
			{            
				buttons: 
				[
					{
						extend: 'copyHtml5',
						className: 'btn btn-light',
						exportOptions: {
							columns: [ 0, ':visible' ]
						}
					},
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						exportOptions: {
							columns: [ 0, ':visible' ]
						}
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i>',
						className: 'btn bg-blue btn-icon dropdown-toggle'
					},
					{
						extend: 'print',
						className: 'btn btn-light',
					},
					// {
					//     extend: 'csv',
					 //     className: 'btn btn-light',
					// }            
			 ]
			},
		columns: [
			{ "data": "no" },
			{ "data": "afsSsaUsername" },
			{ "data": "nama_siswa" },
			{ "data": "rombel" },
			{ "data": "HADIR" },
			{ "data": "IZIN" },
			{ "data": "SAKIT" },
			{ "data": "ALPHA" },
			{ "data": "TERLAMBAT" },
			{ "data": "BOLOS" },
			{ "data": "total" },
		],
		responsive: { details: { type: 'column' } },
		columnDefs: [
		// { width: 400, targets: 3 },
		{ className: 'control',orderable: false, targets:   0 },
		
		{ orderable: false, targets: [3] }
		],
		order: [2, 'asc'],
	});

	$('#absensekolah').click(function(){
		$.ajax({
			type:'PUT',
			url:"{{ route('insert.absen.sekolah.siswa') }}",
			data:{_token: "{{ csrf_token() }}"},
			success:function(respon){
				//console.log(respon);
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

