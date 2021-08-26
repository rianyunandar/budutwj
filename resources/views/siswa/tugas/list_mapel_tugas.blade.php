
@extends('master_siswa')
@section('content')

<!-- Content area -->
<div class="content">

	<div class="row pb-5">
		<span id="result"></span>
		<div class="col-xl-12 col-sm-6">
			<div class="card">
				<div class="card-header bg-dark text-white d-flex justify-content-between">
					<span class="font-size-sm text-uppercase font-weight-semibold">{!! $label !!}</span>			
				</div>
				<div class="card-body">
					<div class="row pb-4">
						<div class="col-md-12">
							
							<button id="refres" type="button" class="btn btn-outline-success btn-sm legitRipple">
								<i class="icon-spinner9 spinner mr-2"></i> Refres
							</button>
						</div>
						
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="akunsiswa" class="table table-striped table-bordered " width="100" style="width: 100%">
									<thead style="background-color: #05405a; color: white;">
										<tr>
											<th>AKSI</th>
											<th>JUDUL</th>
											<th>MAPEL</th>
											<th>MULAI</th>
											<th>BERAKHIR</th>
											<th>KD</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($dataTugas as $data)
										<?php 
										//cek apakah tugas di tampilkan atau tidak
											$tgl_terbit = $data->tgsarTerbit;
											$tgl_berakhir = $data->tgsarBatasTerbit;
											//---------------------------------------------------
											$tglTombolSekarang = strtotime(DateTimeNow()); 
											$tglTombolMulai = strtotime($tgl_terbit);
											$tglTombolAkhir = strtotime($tgl_berakhir);
										?>
											<tr>
												<td>
													<?php 
													//kondisi cek apakah sudah waktunya tombol kerjakan aktif
														if($tglTombolSekarang >= $tglTombolMulai AND $tglTombolSekarang <= $tglTombolAkhir){
																echo'<a href="baca-tugas-siswa/'.encrypt_url($data->el_tugas->tugasId).'"
																	class="btn btn-primary addabsen">
																	&nbsp; KERJAKAN
																</a>';
																//<i class="icon-book"></i> 
															}
															elseif($tglTombolSekarang > $tglTombolAkhir){
																echo'<button class="btn btn-success">
																	<i class="icon-lock2"></i> &nbsp; TUTUP
																</button>';
															}
															else{
																echo'<button class="btn btn-warning">
																	<i class="icon-lock2"></i> &nbsp; Proses
																</button>';
															}
														//end kondisi cek apakah sudah waktunya tombol kerjakan aktif
													?>
												</td>
												<td>{{ $data->el_tugas->tugasJudul }}</td>
												<td>{{ $data->el_tugas->tugasMapelNama }}</td>
												<td>{{ date('d-m-Y H:i:s',strtotime($tgl_terbit)) }}</td>
												<td>{{ date('d-m-Y H:i:s',strtotime($tgl_berakhir) ) }}</td>
												<td>{{ $data->el_tugas->tugasKd }}</td>
											</tr>
										
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
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
$('#refres').click(function(){
		location.reload();
	});
	var tabel_siswa = $('#akunsiswa').DataTable({
			language: {
				search: '<span>Cari:</span> _INPUT_',
				searchPlaceholder: 'Ketikan disini...',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			"order": [[ 3, 'desc' ]],
			
	});

</script>
@endpush

