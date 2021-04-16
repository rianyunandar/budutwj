
@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">

	<!-- 2 columns form -->
	<div class="card border-left-3 border-left-teal rounded-left-0">
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
				<div class="col-md-12" style="text-align: center" >
					<button id="ttotal" style="width: 130px;" type="button" class="btn bg-teal btn-float legitRipple mt-2">
						<i style="font-size: 25px;" class="mi-directions-bike "></i> <span>Total</span>
					</button>
					<button  id="trombel" style="width: 130px;" type="button" class="btn bg-slate btn-float legitRipple mt-2">
						<i style="font-size: 25px;"  class="icon-users4"></i></i> <span>PerRombel</span>
					</button>
					<button id="tsiswa" style="width: 130px;" type="button" class="btn bg-brown btn-float legitRipple mt-2">
						<i style="font-size: 25px;" class="icon-users2"></i></i> <span>Siswa</span>
					</button>
				</div>
			</div><!-- /row-->
			<hr>
			{{-- getSiswaTransportTotal() --}}
			<div class="row mt-3">
				<div class="col-md-12">
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
					@if(empty($_GET['aksi']))
				{{-- if 1 aksi total -----------------------------}}
					@elseif($_GET['aksi']=='total')
					<div class="tab-content">
						@foreach (GetSekolah() as $key => $val)
						<?php if($key==0){ $akte="show active"; }else{ $akte=""; } ?>
						<div class="tab-pane fade {{ $akte }}" id="{{ $val['kode'] }}">
							{{-- <button data-id="TransportTotal<?= $val['id']?>" class="hapuskeyredish btn btn-info"><i class="icon-spinner2 spinner"></i> Refres Data </button> --}}
							<div class="table-responsive">
								<table id="table-dash{{ $val['kode'] }}" class=" table text-nowrap table-bordered">
									<thead>
										<tr>
											<th class="center-table-mryes">JENIS TRANSPORT</th>
											<th class="center-table-mryes">TOTAL</th>
										</tr>
									</thead>
									<tbody>
										@foreach (getSiswaTransportTotal($val['id']) as $value)
											<tr>
												<td>{{ $value['trsNama'] }}</td>
												<td>{{ $value['jumlah'] }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
						<script type="text/javascript">

							$("#table-dash{{ $val['kode'] }}").DataTable({
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
				{{-- if 2 aksi rombel ----------------------------}}
					@elseif($_GET['aksi']=='rombel')
					<div class="tab-content">
						@foreach (GetSekolah() as $key => $val)
						<?php if($key==0){ $akte="show active"; }else{ $akte=""; } ?>
						<div class="tab-pane fade {{ $akte }}" id="{{ $val['kode'] }}">
							{{-- <button data-id="TransportRombel<?= $val['id']?>" class="hapuskeyredish btn btn-info"><i class="icon-spinner2 spinner"></i> Refres Data </button> --}}
							<div class="table-responsive">
								<table id="table-dash{{ $val['kode'] }}" class=" table text-nowrap table-bordered">
									<thead>
										<tr>
											<th class="center-table-mryes">ROMBEL</th>
											@foreach (GetDataTransport() as $valth)
											<th class="center-table-mryes">{{ $valth['trsNama'] }}</th>
											@endforeach
										</tr>
									</thead>
									<tbody>
										<?php $datat = getTransportRombel($val['id']); ?>
										@foreach ($datat as $val1)
												<tr>
													<td>{{ $val1['nama_rombel'] }}</td>
													@foreach ($val1['data_transport'] as $val2)
														<td>{{ $val2['jum_transport'] }}</td>
													@endforeach
												</tr>
										@endforeach
									
									</tbody>
								</table>
							</div>
						</div>
						<script type="text/javascript">

							$("#table-dash{{ $val['kode'] }}").DataTable({
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
				{{-- if 3 aksi siswa -----------------------------}}
					@elseif($_GET['aksi']=='siswa')
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<select id="idtransort" data-placeholder="Pilih Tranportasi"  name="transpot" id="transpot"  class="form-control form-control-select2" data-fouc>
										<option></option>
										@foreach ($getTraspot as $data)
											<option {{ selectAktif(@$_GET['id'],encrypt_url($data->trsId))  }} value="{{ encrypt_url($data->trsId) }}">{{ $data->trsNama}}</option>
										@endforeach
										</select>
								</div>
							</div>
						</div>
						@if(!empty($_GET['id']))
							<div class="row">
								<div class="col-md-12">
									<div class="tab-content">
										@foreach (GetSekolah() as $key => $val)
											<?php if($key==0){ $akte="show active"; }else{ $akte=""; } ?>
											<div class="tab-pane fade {{ $akte }}" id="{{ $val['kode'] }}">
												{{-- <button data-id="TransportSiswas<?= decrypt_url($_GET['id']).$val['id']?>" class="hapuskeyredish btn btn-info"><i class="icon-spinner2 spinner"></i> Refres Data </button> --}}
												<div class="table-responsive">
													<table id="table-dash{{ $val['kode'] }}" class=" table text-nowrap table-bordered">
														<thead>
															<tr>
																<th class="center-table-mryes">USERNMAE</th>
																<th class="center-table-mryes">NAMA</th>
																<th class="center-table-mryes">ROMBEL</th>
															</tr>
														</thead>
														<tbody>
															@foreach (getTransportSiswas($val['id'],$_GET['id']) as $val1)
																	<tr>
																		<td>{{ $val1['username'] }}</td>
																		<td>{{ $val1['fullname']}}</td>
																		<td>{{ $val1['rombel']}}</td>
																	</tr>
															@endforeach
														
														</tbody>
													</table>
												</div>
											</div>
											<script type="text/javascript">
					
												$("#table-dash{{ $val['kode'] }}").DataTable({
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
								</div>
							</div>
						@endif
					@endif
				</div>
			</div>
		</div> 	<!-- / card-body-->
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
	$('#ttotal').click(function() {
		location.replace("?aksi=total");
	});
	$('#trombel').click(function() {
		location.replace("?aksi=rombel");
	});
	$('#tsiswa').click(function() {
		location.replace("?aksi=siswa");
	});
	$('#idtransort').change(function() {
		var id = $(this).val();
		location.replace("?aksi=siswa&id="+id);
	});
	$('.hapuskeyredish').click(function() {
		var id = $(this).data('id');
		$.ajax({
				type:'GET',
				url:"{{ url('hapus-key-redis') }}"+"/"+id ,
				success:function(respon){
					//console.log(respon);
					setInterval(function(){ location.reload(true); }, 500);
				}
		});
	});
</script>
@endpush

