@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; }else{ $getrbl =''; }
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }
if(!empty($_GET['skl'])){ 
	$url = 'guru/json-absen-sekolah-guru?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&thn='.$getthn; 
} else{ $url='guru/json-absen-sekolah-guru';}

?>
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
			<div class="row ">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-lg-3">
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">{{$skl->sklNama}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-2">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getRombel as $rbl)
								<option {{ selectAktif($getrbl, encrypt_url($rbl->rblId)) }}   value="{{ encrypt_url($rbl->rblId) }}">{{$rbl->master_kelas->klsKode.' '.$rbl->rblNama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Tahun"  name="thn" id="thn"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getBulanTahunAbsen as $thn)
								<option {{ selectAktif($getthn, encrypt_url($thn->tahun))}}  value="{{ encrypt_url($thn->tahun) }}">{{$thn->tahun}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach (getBulan() as $bln)
								<option {{ selectAktif($getbln, encrypt_url($bln))  }} value="{{ encrypt_url($bln) }}">{{ bulanIndo($bln)}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<table id="tabel2" class="table table-striped table-bordered" width="100" style="width: 100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>Aksi</th>
								<th>Username</th>
								<th>Nama Siswa</th>
								<th>Tanggal</th>
								<th>Jam In</th>
								<th>Jam Out</th>
								<th>Status Absen</th>
								<th>Rombel</th>
								{{-- <th>Jurusan</th> --}}
								<th>Sekolah</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
	</div>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
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
	$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var rbl = $('#rbl').val();
		var thn = $('#thn').val();
		var bln = $('#bln').val();
  	location="?skl="+skl+"&rbl="+rbl+"&thn="+thn+"&bln="+bln+" ";
	});
	var tabel = $('#tabel2').DataTable({
		processing: true,
		//serverSide: true,
		ajax: '{{ url($url) }}',
		type: "GET",
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			emptyTable: "Data Tidak Ada , Silahkan Pilih Rombel Terlebih Dahulu",
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		autoWidth: false,
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
				{ "data": "aksi" },
				{ "data": "username" },
				{ "data": "namasiswa" },
				{ "data": "afsDatetime" },
				{ "data": "afsIn" },
				{ "data": "afsOut" },
				{ "data": "status_absen" },
				{ "data": "namarombel" },
				// { "data": "jurusan" },
				{ "data": "sekolah" },
			],
		
	scrollX: true,
			scrollY: '700px',
			scrollCollapse: true,
			fixedColumns: {
				leftColumns: 1,
			},
	order: [2, 'asc'],
});

  $(document).on('click','#deletejurusan',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");
		var cek = confirm('Apakah Anda Yakin Akan Menghapus Data Ini ?');
		if(cek==true){
			$.ajax({
				type:'delete',
				url:id+'/deletejurusan',
				data: { _token: token,id: id },
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
							tabel.ajax.reload();
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
		}
  });
</script>
@endpush

