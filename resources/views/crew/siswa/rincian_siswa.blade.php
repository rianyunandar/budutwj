@extends('master')
@section('title')
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
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-pills">
						@foreach (GetSekolah() as $key => $val)
							<?php if($key==0){ $akt="active"; }else{ $akt=""; } ?>
							<li class="nav-item"><a href="#{{ $val['kode'] }}" class="nav-link {{ $akt }}" data-toggle="tab"><i class="icon-home2"></i> {{ $val['nama'] }}</a></li>
						@endforeach
					</ul>
				</div>
			</div>{{-- /row --}}
			<div class="row">
				<div class="col-md-12">
					<div class="tab-content">
						@foreach (GetSekolah() as $key => $val)
						<?php if($key==0){ $akte="show active"; }else{ $akte=""; } ?>
						<div class="tab-pane fade {{ $akte }}" id="{{ $val['kode'] }}">
							<div class="table-responsive">
								<table id="table-rincisiswa{{ $val['kode'] }}" class=" table text-nowrap table-bordered">
									<thead>
										<tr>
											<th class="center-table-mryes">ROMBEL</th>
											<th class="center-table-mryes">P</th>
											<th class="center-table-mryes">L</th>
											<th class="center-table-mryes">PER ROMBEL</th>
											<th class="center-table-mryes">PER JURUSAN</th>
											<th class="center-table-mryes">PER JENJANG KELAS</th>
										</tr>
									</thead>
									<tbody>
										{{-- {{ dd( GetRincianSiswaRombel($val['id']) ) }}  --}}
										<?php $data=GetRincianSiswaRombel($val['id']); $total_kelas=$total_rombel=0; ?>
										@foreach ($data as $key => $value)
										<tr>
											<td>{{ $value['rombel'] }}</td>
											<td class="center-table-mryes">{{ $value['jum_p'] }}</td>
											<td class="center-table-mryes">{{ $value['jum_l'] }}</td>
											<td class="center-table-mryes">{{ $value['jum_perrombel'] }}</td>
											<?php 
											$total_rombel += $value['jum_perrombel'];
                      if(@$data[$key+1]['jurusan'] != $value['jurusan']) {
												echo"<td class='center-table-mryes'>".$total_rombel."</td>";
												$total_rombel=0;
											}else{ echo"<td></td>"; } ?>
											<?php 
											$total_kelas += $value['jum_perrombel'];
                      if(@$data[$key+1]['kelas'] != $value['kelas']) {
												echo"<td class='center-table-mryes'>".$total_kelas."</td>";
												$total_kelas=0;
                      }else{ echo"<td></td>"; } ?>
										
										</tr>
										@endforeach
										
									</tbody>
								</table>
							</div>
						</div>
						<script type="text/javascript">

							$("#table-rincisiswa{{ $val['kode'] }}").DataTable({
								dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
								language: {
									search: '<span>Cari:</span> _INPUT_',
									searchPlaceholder: 'Ketik untuk filter...',
									lengthMenu: '<span>Tampil Baris:</span> _MENU_',
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
						
							});
						
						 
						</script>
						@endforeach
					</div>
					<br>
				</div>  
			</div>{{-- /row --}}
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

@endpush

