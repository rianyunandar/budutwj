@extends('master')
@section('titile', 'Lihat Semester')
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
			<ul class="nav nav-tabs nav-tabs-highlight">
				<li class="nav-item"><a href="#left-icon-tab1" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-1"></i> Lihat Data Semester</a></li>
				@if(AksiInsert())
				<li class="nav-item"><a href="#left-icon-tab2" class="nav-link" data-toggle="tab"><i class="icon-plus3 mr-1"></i> Tambah Semester</a></li>
				</li>
				@endif
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="left-icon-tab1">
					<div class="row">
						<div class="col-md-12">
							<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
								<thead style="background-color: #05405a; color: white;">
									<tr>
										<th>Kode Semester</th>
										<th>Nama Semester</th>
										<th>Tahun Ajaran</th>
										<th>Nama Sekolah</th>
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($getSemester as $sm)
										<tr>
											<td>{{$sm->smKode}}</td>
											<td>{{$sm->smNama}}</td>
											<td>{{$sm->tahun_ajaran->tajrNama}}</td>
											<td>{{$sm->master_sekolah->sklKode}}</td>
											<td>
												@if ($sm->smIsActive==1)
												<span class="badge badge-primary">Aktif</span>
												@else
												<span class="badge badge-danger">Tidak Aktif</span>
												@endif
											</td>
											<td></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="left-icon-tab2">
					@if(AksiInsert())
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">SEKOLAH</label>
								<div class="col-lg-9">
								<select required data-placeholder="Pilih Sekolah"  name="rbl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getSekolah as $skl)
									<option value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
								</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Tahun Ajaran</label>
								<div class="col-lg-9">
								<select required data-placeholder="Pilih Tahun Ajaran"  name="ta" id="ta"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getTa as $ta)
									<option value="{{ $ta->tajrId}}">{{ $ta->tajrNama}}</option>
									@endforeach
								</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Kode Semester</label>
								<div class="col-lg-9">
									<input required type="text" name="smkode" class="form-control" placeholder="Misal SMGE20">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-2 col-form-label">Nama Semester</label>
								<div class="col-lg-9">
									<input required type="text" name="smnama" class="form-control" placeholder="Nama Semester">
								</div>
							</div>

							<button id="rombel" class="btn btn-primary"><i class="icon-paperplane"></i> Simpan</button>
						</div>
					</div>
					@endif
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
{{-- js datables agar ada button copy excel --}}
<script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js') }}"></script>
{{-- toolip popout --}}
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">

	var tabel = $('#tabel').DataTable({
		processing: true,
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

</script>
@endpush

