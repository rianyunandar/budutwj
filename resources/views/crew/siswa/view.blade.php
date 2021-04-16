@extends('master')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header  header-elements-inline " >
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
			@if(AksiInsert())
			<div class="row mt-2">
				<div class="col-md-6">
					<a href="{{ url('crew/addsiswa')}}" class="btn btn-primary"><i class="icon-user-plus"></i> Tambah Akun Siswa</a>
				</div>
			</div>
			@endif
			<div class="row">
				<div class="col-md-12">
					<table id="akunsiswa" class="table table-striped table-bordered " width="100" style="width: 100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Nama Depan</th>
								<th>Nama Belakang</th>
								<th>Jurusan</th>
								<th>Aksi</th>
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
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
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
{{-- js datables agar ada button copy excel --}}
{{-- <script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js') }}"></script> --}}
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

	var tabel_siswa = $('#akunsiswa').DataTable({
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Type to filter...',
			lengthMenu: '<span>Tampil:</span> _MENU_',
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
		processing: true,
		serverSide: true,
		responsive: true,
		autoWidth: false,
		ajax: '{{ route('jsonsiswa') }}',
		columns: [
		{ "data": "no" },
		{ "data": "ssaUsername" },
		{ "data": "ssaFirstName" },
		{ "data": "ssaLastName" },
		{ "data": "master_jurusan.jrsSlag" },
		
		{"mRender": function ( data, type, row ) 
			{
			@if(AksiUpdate())
			$btn = '<a title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" href="edit-siswa/'+row.idsiswa+'"><i class="icon-pencil7"> Edit</i></a>';
      //$btn += '<a title="Hapus Data" id="deletesiswa" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'+row.idsiswa+'"><i class="icon-trash"> Hapus</i></a>';
			$btn += '<a title="Reset Password" id="resetpass" class="dropdown-item btn btn-sm btn-outline bg-warning text-warning border-warning legitRipple" data-id="'+row.idsiswa+'"><i class="icon-reset"> Reset Password </i></a>';
      
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
	],
	// columnDefs: [{ 
	// 		orderable: false,
	// 		width: 100,
	// 		targets: [ 4 ]
	// 	}],
	responsive: {
		details: {
			type: 'column'
		}
	},
	columnDefs: [
	{
		className: 'control',
		orderable: false,
		targets:   0
	},
	{ 
		width: "100px",
		targets: [5]
	},
	{ 
		orderable: false,
		targets: [5]
	}
	],
	order: [2, 'asc'],
	

});

  $(document).on('click','#deletesiswa',function(e){
		var id = $(this).data("id");
		//alert(id);
		var token = $("meta[name='csrf-token']").attr("content");
		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Data Ini ?</h3><center> ',
			timeout: false,
			modal: true,
			layout: 'center',
			closeWith: 'button',
			type: 'confirm',
			buttons: [
			Noty.button('Cancel', 'btn btn-link', function () {
				notyConfirm.close();
			}),

			Noty.button('Hapus <i class="icon-trash ml-2"></i>', 'btn bg-danger ml-1', function () {
				$.ajax({
					type:'PUT',
					url:id+'/deletesiswa',
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
								tabel_siswa.ajax.reload();
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
				notyConfirm.close();
			},
			{id: 'button1', 'data-status': 'ok'}
			)
			]
		}).show();
		
  });
	$(document).on('click','#resetpass',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");
		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Reset Password ? </h3><center> ',
			timeout: false,
			modal: true,
			layout: 'center',
			closeWith: 'button',
			type: 'confirm',
			buttons: [
			Noty.button('Cancel', 'btn btn-link', function () {
				notyConfirm.close();
			}),

			Noty.button('YA <i class="icon-trash ml-2"></i>', 'btn bg-warning ml-1', function () {
				$.ajax({
					type:'PUT',
					url:'{{ route("reset.password.siswa") }}',
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
								tabel_siswa.ajax.reload();
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
				notyConfirm.close();
			},
			{id: 'button1', 'data-status': 'ok'}
			)
			]
		}).show();
	});
</script>
@endpush

