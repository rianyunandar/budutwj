@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-md-12">
		
			<div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title">{!! $label !!}</h6>
					<button id="refres" type="button" class="btn btn-outline-success btn-sm legitRipple">
						<i class="icon-spinner9 spinner mr-2"></i> Refres Mapel
					</button>
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
					</div>
				</div>

				<div class="card-body">
					{{-- data anggota kelas --}}
					{{-- <div class="row">
						<div class="col-md-2">

						</div>
					</div> --}}
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="akunsiswa" class="table table-striped table-bordered " width="100" style="width: 100%">
									<thead style="background-color: #05405a; color: white;">
										<tr>
											<th>AKSI</th>
											<th>NAMA MAPEL</th>
											<th>HARI</th>
											<th>JAM MULAI</th>
											<th>JAM BERAKHIR</th>
											<th>NAMA GURU</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$jamsekarang = strtotime(date("H:i"));
										?>
										@foreach($getjadwal as $data)
										<?php
											$jamin= strtotime($data->majdJamMulai);
											$jamout = strtotime($data->majdJamAkhir);
										?>	
											@if($data->madjTampilkan == 1)
											<tr>
												<td>
													<?php 
														if($jamsekarang >= $jamin AND  $jamsekarang <= $jamout){
																echo'<button data-mapel="'.$data->majdId.'" 
																	class="btn btn-primary addabsen">
																	<i class="fa fa-check"></i> Klik Absen
																</button>';
															}
															else if($jamsekarang <= $jamin AND  $jamsekarang <= $jamout){
																echo'<button disabled  class="btn btn-danger"><i class="fa fa-times"></i> Belum Mulai</button>';
															}
															else{
																echo'<button disabled class="btn btn-warning">Berakhir </button>';
															}	
													?>
												</td>
												<td>{{ $data->majdNama }}</td>
												<td>{{ hariIndo($data->majdHari) }}</td>
												<td>{{ $data->majdJamMulai }}</td>
												<td>{{ $data->majdJamAkhir }}</td>
												<td>{{ $data->user_guru->ugrFirstName.' '.$data->user_guru->ugrLastName }}</td>
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /directory -->
		</div>
	</div>
</div>
<br><br>
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
@push('js_bawah')
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
				searchPlaceholder: 'Ketikan disini...',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			
	});
	$('.addabsen').click(function(){
		var mapel = $(this).data('mapel');
		$.ajax({
			type:'PUT',
			url:"{{ route('insert.absen.mapel.siswa') }}",
			data:{mapel: mapel,_token: "{{ csrf_token() }}"},
			success:function(respon){
				//console.log(respon);
				if(respon.success){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					//setInterval(function(){ location.reload(true); }, 1000);
				}
				if(respon.warning){
						new Noty({
						theme: ' alert alert-warning alert-styled-left p-0',
						text: respon.warning,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
				}

				},
			error: function (respon) {
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
				 }

		});

	});
	$('#refres').click(function(){
		location.reload();
	});

</script>
@endpush

