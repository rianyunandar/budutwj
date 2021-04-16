@extends('master')
@section('titile', 'Tambah Siswa')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header bg-dark header-elements-inline">
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
			{{-- <div class="table-responsive-sm"> --}}
			<table id="siswa_all" class="table datatable-fixed-left table-striped table-bordered datatable-column-search-inputs" width="100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>Username</th>
								<th>Aksi</th>
								<td>Foto</td>
								<th>Nama</th>
								<th>Kelas</th>
								<th>Jurusan</th>
								<th>Jsk</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
							  <th>NIS</th>
								<th>NISN</th>
								<th>NIK</th>
								<th>Agama</th>
								<th>Alamat</th>
								<th>Asal SMP</th>
								<th>No Hp</th>
								<th>Ayah</th>
								<th>No Hp Ayah</th>
								<th>Ibu</th>
								<th>No Hp Ibu</th>
								<th>Status</th>
								<th>Status Keadaan</th>
								<th>Keterangan</th>
								<th>Catatan</th>
								<th>Tgl Update</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<td>Username</td>
								<td>Aksi</td>
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
								<td>Status Keadaan</td>
								<td>Keterangan</td>
								<td>Catatan</td>
								<td>Tgl Update</td>
							</tr>
						</tfoot>
			</table>
			{{-- </div> --}}
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
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js')}}"></script>

<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

<!-- pluginnya form select-->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<!-- pluginnya buat export -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_api.js') }}"></script>


@endpush
@push('js_atas2')

@endpush
@push('jsku')
<script type="text/javascript">
	//$.fn.dataTable.ext.errMode = 'throw';
	var tabel_siswa = $('#siswa_all').DataTable({
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan to filter...',
			lengthMenu: '<span>Tampil:</span> _MENU_ <span>Data</span>',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
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
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'pdfHtml5',
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
		"retrieve": true,
    "lengthMenu": [[10, 50, 100, 150, 200, 300, -1], [10, 50, 100, 150, 200, 300, "All"]],
		"processing": true,
    "serverSide": true,
		//responsive: true,
		//autoWidth: true,
		ajax:'{{route('json.siswa.off')}}',

		columns: [
		{ "data": "username"},//ssaUsername
		{"mRender": function ( data, type, row ) 
			{
			@if(AksiUpdate())
			$btn = '<a title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" href="edit-siswa/'+row.idsiswa+'"><i class="icon-pencil7"> Edit</i></a>';
      //$btn += '<a title="Hapus Data" id="deletesiswa" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'+row.idsiswa+'"><i class="icon-trash"> Hapus</i></a>';
      return '<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">'+
        '<li class="list-inline-item dropdown">'+
          '<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>'+
          '<div class="dropdown-menu dropdown-menu-right">'+
            $btn  
          '</div>'+ 
        '</li>'+ 
      '</ul>';
			@else
			return null;
			@endif
			}
		},
			{ "data": "foto"},
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
			{ "data": "status_keadaan"},
			{ "data": "ktr" },
			{ "data": "catatan" },
			{ "data": "updateat" }//ssaUpdated
		],
		scrollX: true,
		//scrollY: '700px',
    scrollCollapse: true,
    fixedColumns: {
			leftColumns: 1,
		}
		
  });

 
</script>
@endpush

