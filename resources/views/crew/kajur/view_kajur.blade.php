@extends('master')
@section('content')
<!-- Content area -->
<div class="content">
	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header bg-indigo header-elements-inline">
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
			<div class="row ">
				<div class="col-md-6">
					<a href="{{ route('tambah.kajur') }}" class="btn btn-primary"><i class="icon-plus3"></i> Tambah Kajur</a>
				</div>
			</div>
			@endif
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive-sm">
					<table id="tabel" class="table table-striped table-bordered  " width="100%">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>AKSI</th>
								<th>USER NAME</th>
								<th>NAMA GURU</th>
								<th>KAJUR KODE</th>
								<th>KAJUR NAMA</th>
								<th>KODE SEKOLAH</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<!-- Disabled backdrop -->
	<div id="modal_backdrop" class="modal fade" data-backdrop="false" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Kajur</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<input type="hidden" name="id2" id="id2" value="">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">Pilih Jurusan</label>
						<div class="col-lg-9">
							<select data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select" data-fouc>
								@foreach ($getJurusan as $jrs)
								<option value="{{ encrypt_url($jrs->jrsId) }}">{{$jrs->jrsNama}}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" id="update">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /disabled backdrop -->

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

	$(document).on('click','#edit',function(e){
		var id = $(this).data("id");
		var id2 = $('#id2').val(id);
	});
	$(document).on('click','#update',function(e){
		var jrs = $('#jrs').val();
		var id2 = $('#id2').val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
					type:'POST',
					url:"{{ route('update.kajur') }}",
					data: { _token: token,jrs: jrs,id:id2 },
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


	});
	$(document).on('click','#delete',function(e){
		var id = $(this).data("id");
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
					url:id+'/delete-kajur',
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
				notyConfirm.close();
			},
			{id: 'button1', 'data-status': 'ok'}
			)
			]
		}).show();
	});

	var tabel = $('#tabel').DataTable({
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		
		processing: true,
		//serverSide: true,
		responsive: true,
		autoWidth: false,
		ajax: '{{ route('json.kajur') }}',
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
					    
			 ]
			},
			
		columns: [
			{ "data": "aksi"},
			{ "data": "ugrUsername"},
			{ "data": "namaguru"},
			{ "data": "master_jurusan.jrsSlag"},
			{ "data": "master_jurusan.jrsNama"},
			{ "data": "master_sekolah.sklKode"},
		],
		columnDefs: [
	    
	    { "width": "40%", "targets": 2 },
	    
	  ]	
		
});

	
  
  // $(document).on('click','#delete',function(e){
		// var id = $(this).data("id");
		// var token = $("meta[name='csrf-token']").attr("content");
		// var cek = confirm('Apakah Anda Yakin Akan Menghapus Data Ini ?');
		// if(cek==true){
		// 	$.ajax({
		// 		type:'PUT',
		// 		url:id+'/delete-kajur',
		// 		data: { _token: token,id: id },
		// 		success:function(respon){
		// 			console.log(respon);
		// 				if(respon.success){
		// 					new Noty({
		// 					theme: ' alert alert-success alert-styled-left p-0 bg-white',
		// 					text: respon.success,
		// 					type: 'success',
		// 					progressBar: false,
		// 					closeWith: ['button']
		// 					}).show();
		// 					tabel.ajax.reload();
		// 				}
		// 				if(respon.error){
		// 					new Noty({
		// 					theme: ' alert alert-danger alert-styled-left p-0',
		// 					text: respon.error,
		// 					type: 'error',
		// 					progressBar: false,
		// 					closeWith: ['button']
		// 					}).show();
		// 				}
		// 			}
		// 	});
		// }
  // });
</script>
@endpush

