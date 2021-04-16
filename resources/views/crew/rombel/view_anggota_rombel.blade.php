@extends('master')
@section('titile', 'Lihat Jurusan')
@section('content')
<?php 

if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; }else{ $getrbl =''; }

if(!empty($_GET['rbl'])){ 
	$url = 'crew/json-anggota-rombel?rbl='.$getrbl; 
} else{ $url='crew/json-anggota-rombel';}

?>
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
			<div class="row ">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getRombel as $rbl)
								<option {{ selectAktif($getrbl, encrypt_url($rbl->rblId)) }}   value="{{ encrypt_url($rbl->rblId) }}">{{$rbl->master_kelas->klsKode.' '.$rbl->rblNama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Data</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>USERNAME</th>
								<th>NAMA</th>
								<th>ROMBEL</th>
								<th>JURUSAN</th>
								<th>SEKOLAH</th>
								
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
@push('js_atas2')
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
		
		var rbl = $('#rbl').val();
  	location="?rbl="+rbl;
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
						
					},
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						
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
		{ "data": "user_siswa.ssaUsername" },
		{ "data": "namasiswa" },
		{ "data": "namarombel" },
		{ "data": "master_jurusan.jrsSlag" },
		{ "data": "master_sekolah.sklNama" },
		
	],
	responsive: { details: { type: 'column' } },
	columnDefs: [
	{ width: 400, targets: 2 },
	{ width: 300, targets: 5 },
	{ className: 'control',orderable: false, targets:   0 },
	{ width: "100px", targets: [3] },
	{ orderable: false, targets: [3] }
	],
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

