
@extends('master_siswa')
@section('content')
<!-- Content area -->
<div class="content">
	@if(empty($_GET['aksi']))	
		<div class="row pb-5">
			<span id="result"></span>
			<div class="col-xl-12 col-sm-6">
				<!-- Account settings -->
				<!-- Dark header, transparent footer -->
				<div class="card">
					<div class="card-header bg-dark text-white d-flex justify-content-between">
						<span class="font-size-sm text-uppercase font-weight-semibold">{!! $label !!}</span>
						{{-- <span class="font-size-sm text-uppercase font-weight-semibold">Due in 12 days</span> --}}
					</div>
					<div class="card-body">
						<ul class="list-group list-group-flush ">
							@foreach ($dataJadwal as $data)
							<?php $kodeMapel = encrypt_url($data['kode_mapel']); ?>
							<a href="?aksi=daftar&mapel={{ $kodeMapel }}" class="list-group-item list-group-item-action border-bottom">
								<span class="font-weight-semibold">
									{{ $data['nama_mapel'] }}
								</span>
								<span style="font-size: 12px;" class="badge bg-primary ml-auto">{{ $data['jml_materi'] }} Materi</span>
							</a>
							@endforeach
						</ul>
					</div>

					{{-- <div class="card-footer bg-transparent d-flex justify-content-between border-top-0 pt-0">
						<span class="text-muted">Last updated 3 mins ago</span>
						<span>
							<i class="icon-star-full2 font-size-base text-warning-300"></i>
							<i class="icon-star-full2 font-size-base text-warning-300"></i>
							<i class="icon-star-full2 font-size-base text-warning-300"></i>
							<i class="icon-star-full2 font-size-base text-warning-300"></i>
							<i class="icon-star-half font-size-base text-warning-300"></i>
							<span class="text-muted ml-2">(12)</span>
						</span>
					</div> --}}
				</div>
				<!-- /dark header, transparent footer -->
				<!-- /account settings -->
			</div>
		</div>
	@elseif($_GET['aksi']=='daftar')

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
							<a class="btn btn-dark" href="{{ route('list.jadwal.mapel') }}"><i class="icon-arrow-left52"></i> Pilih Mapel</a>
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
											<th>KD</th>
											<th>TERBIT</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($datamateri as $data)
										<?php 
										$tgl_terbit = $data->mtraTerbit;
										$tglSekarang = strtotime(DateTimeNow()); 
										$tglTerbit = strtotime($tgl_terbit); 
										?>
											<tr>
												<td>
													<?php 
														if($tglSekarang >= $tglTerbit){
																echo'<a href="baca-materi-siswa/'.encrypt_url($data->el_materi->materiId).'"
																	class="btn btn-primary addabsen">
																	<i class="icon-book"></i> &nbsp; Baca
																</a>';
															}
															else{
																echo'<button class="btn btn-warning">
																	<i class="icon-lock2"></i> 
																</button>';
															}
													?>
												</td>
												<td>{{ $data->el_materi->materiJudul }}</td>
												<td>{{ $data->el_materi->materiKd }}</td>
												<td>{{ date('d-m-Y H:i:s',$tglTerbit ) }}</td>
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
	@else
	@endif
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

