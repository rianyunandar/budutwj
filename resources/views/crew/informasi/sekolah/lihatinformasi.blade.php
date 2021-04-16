@extends('master')
@section('titile')
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
			<div class="row">
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>AKSI</th>
								<th>JUDUL</th>
								<th>TUJUAN</th>
								<th>PEMBUAT</th>
								<th>TANGGAL</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($getInfo as $data)
							<tr>
								<td>{{ $no++ }}</td>
								<td>
									<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
										<li class="list-inline-item dropdown">
											<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
						
											<div class="dropdown-menu dropdown-menu-right">
												<a href="edit-informasi-sekolah/{{ encrypt_url($data->infoId) }}" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>
												<a id="delete"  data-id="{{ encrypt_url($data->infoId) }}" title="Hapus Data" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-trash"> Hapus</i></a>
											</div>
										</li>
									</ul>
								</td>
								<td>{{ $data->infoJudul }}</td>
								<td>{{ $data->infoTujuan }}</td>
								<td>{{ $data->infoNamaUser }}</td>
								<td>{{ formatTanggalJamIndo($data->infoCreated) }}</td>
							</tr>	

							@endforeach
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
<!-- pluginnya buat export -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endpush
@push('js_atas2')
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
{{-- js datables agar ada button copy excel --}}
<script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js') }}"></script>
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

	var tabel = $('#tabel').DataTable({
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
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
					url:id+'/deleteinfo',
					data: { _token: token,id: id },
					success:function(respon){
						// console.log(respon);
						if(respon.success){
							new Noty({
								theme: ' alert alert-success alert-styled-left p-0 bg-white',
								text: respon.success,
								type: 'success',
								progressBar: false,
								closeWith: ['button']
							}).show();
							//tabel.ajax.reload();
							setInterval(function(){ location.reload(true); }, 1000);
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

