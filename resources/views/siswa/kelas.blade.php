@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Directory -->
			<div class="text-center mb-3 py-2">
				<h3 class="font-weight-semibold mb-1">{{ $rombel }}</h3>
				<span class="text-muted d-block">{{ $jurusan }}</span>
				<span class="text-muted d-block">{{ $sekolah }}</span>
				<span class="text-muted d-block">{{ $tahun_pelajara }}</span>
			</div>

			<div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title"><b>Anggota Kelas</b></h6>
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
						{{-- <a class="list-icons-item" data-action="reload"></a>
						<a class="list-icons-item" data-action="remove"></a> --}}
					</div>
				</div>

				<div class="card-body">
				
					{{-- data anggota kelas --}}
					<div class="row">
						<div class="col-md-12">
							<table id="akunsiswa" class="table table-striped table-bordered " width="100" style="width: 100%">
								<thead style="background-color: #05405a; color: white;">
									<tr>
										<th>USERNAME</th>
										<th>FOTO</th>
										<th>NAMA</th>
										<th>ROMBEL</th>
										<th>JURUSAN</th>
										<th>STATUS</th>
										<th>AGAMA</th>
										<th>NO HP</th>
										<th>NO WA</th>
										<th>ASAL SMP</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- /directory -->
		</div>
	</div>
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

{{-- Upload --}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>

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
	var tabel_siswa = $('#akunsiswa').DataTable({
			language: {
				search: '<span>Cari:</span> _INPUT_',
				searchPlaceholder: 'Type to filter...',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			processing: true,
			//serverSide: true,
			responsive: true,
			ajax: '{{ route('json.anggota.kelas') }}',
			columns: [
			{ "data": "username" },
			{"mRender": function ( data, type, row ) 
				{
					var foto ='<img src="'+row.foto+'" class="rounded" width="50" height="50" alt="'+row.namasiswa+'">';
					return foto;
				}
			},
			{ "data": "namasiswa" },
			{ "data": "namarombel" },
			{ "data": "jurusan" },
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
			{ "data": "agama" },
			{ "data": "hp" },
			{ "data": "hpwa" },
			{ "data": "asalsmp" },
		],
	


	});


</script>
@endpush

