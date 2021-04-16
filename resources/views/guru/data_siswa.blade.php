@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; }else{ $getrbl =''; }
if(!empty($_GET['jrs'])){ $getjrs =$_GET['jrs']; }else{ $getjrs =''; }
if(!empty($_GET['siswa'])){ $getsiswa=$_GET['siswa']; }else{ $getsiswa =''; }
if(!empty($_GET['rbl'])){ 
	//$url = 'guru/guru-data-siswa-json?skl='.$getskl.'&rbl='.$getrbl.'&jrs='.$getjrs.'&siswa='.$getsiswa.'&siswa2='; 
	$url = 'guru/guru-data-siswa-json?rbl='.$getrbl.'&siswa='.$getsiswa.'&hanyapembantu='; 
}
else{
	$url ='';
}

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
			<div class="row mb-2">
				<div class="col-md-12">
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
						<select name="siswa" id="siswa" style="display: none"></select>
						{{-- <div class="form-group row">
							<label class="col-lg-3 col-form-label">Siswa</label>
							<div class="col-lg-7">
								<select required data-placeholder="Pilih Siswa"  name="siswa" id="siswa"  class="form-control select-search" data-fouc>
								</select>
							</div>
						</div> --}}
					</fieldset>
					<button id="cariabsen" class="btn btn-info"><i class="icon-search4"></i> Cari Data</button>
				</div>
			</div>
			@if(!empty($getrbl))
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
					<table id="tabel2" class="table table-striped table-bordered" width="100" style="width: 100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<td>Username</td>
								<td>Foto</td>
								<td>Nama</td>
								<td>Kelas</td>
								<td>Jurusan</td>
								<td>Jsk</td>
								<td>Tempat Lahir</td>
								<td>Tanggal Lahir</td>
							  <td>NIS</td>
								<td>NISN</td>
								<td>NIK</td>
								<td>Agama</td>
								<td>Alamat</td>
								<td>Asal SMP</td>
								<td>No Hp</td>
								<td>Ayah</td>
								<td>No Hp Ayah</td>
								<td>Ibu</td>
								<td>No Hp Ibu</td>
								<td>Status</td>
								<td>Keterangan</td>
								<td>Tgl Update</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					</div>
				</div>
			</div>
			@endif
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
		// $('#rbl').change(function(){
		// 	var id=$(this).val();
		// 	$('#siswa').empty();
		// 	$.ajax({
		// 		url : "{{ route('pilih.siswa') }}",
		// 		method : "POST",
		// 		data : {id: id,_token: "{{ csrf_token() }}"},
		// 		async : false,
		// 		dataType : 'json',
		// 		success: function(data){
		// 			//console.log(data);
		// 			var html = '<option value="">Pilih Siswa</option>';
		// 			var i;
		// 			html += '<option value="all">All (Semua Siswa) </option>';
		// 			for(i=0; i<data.length; i++){
		// 				html += '<option value='+data[i].idsiswa+'>'+data[i].namasiswa+'</option>';
		// 			}

		// 			$('#siswa').html(html);
		// 		}
		// 	});
		// 	return false;
		// });

	$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var rbl = $('#rbl').val();
		var jrs = $('#jrs').val();
		var siswa = $('#siswa').val();
		if(skl==''){
			alert('Pilih Sekolah');
		}
		else if(rbl==''){
			alert('Pilih Rombel');
		}
		else if(jrs==''){
			alert('Pilih Jurusan');
		}
		else{
			//location="?skl="+skl+"&rbl="+rbl+"&jrs="+jrs+"&siswa="+siswa+" ";
			location="?rbl="+rbl+"&siswa="+siswa+" ";
		}
		
  	
	});
	var url2 = "{{ $url }}";
	if(url2 != null){
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
				{ "data": "username"},//ssaUsername
				{"mRender": function ( data, type, row ) 
					{
						var foto ='<img src="'+row.foto+'" class="rounded" width="50" height="50" alt="'+row.namasiswa+'">';
						return foto;
					}
				},
				{ "data": "namasiswa"},
				{ "data": "namarombel"},
				{ "data": "jurusan" },//master_jurusan.jrsSlag
				{ "data": "jsk"},
				{ "data": "tpl"},
				{ "data": "tgl" },
				{ "data": "nis" },
				{ "data": "nisn" },
				{ "data": "nik"},
				{ "data": "agama" },
				{ "data": "alamat" },
				{ "data": "asalsmp"},
				{ "data": "hp" },
				{ "data": "ayah" },
				{ "data": "hpayah"},
				{ "data": "ibu"},
				{ "data": "hpibu"},
				// { "data": "status" },
				{"mRender": function ( data, type, row ) 
					{
						if(row.status == 1){ //ssaIsActive
							var btn ='<span class="badge badge-primary">Aktif</span>';
						}
						else{
							var btn ='<span class="badge badge-danger">Tidak Aktif</span>';
						}
						return btn;
					}
				},
				{ "data": "ktr" },
				{ "data": "updateat" }//ssaUpdated
			],
		});
	}


  
</script>
@endpush

