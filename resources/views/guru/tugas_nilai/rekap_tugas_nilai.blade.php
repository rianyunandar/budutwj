@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; } else{ $getrbl =''; }
?>
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
		<form id="insert"  data-route="{{ route('insert.nilai.tugas.guru') }}">
			{{ csrf_field() }}
			
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					
					<div class="alert alert-primary alert-styled-left alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						<span class="font-weight-semibold">PETUNJUK</span>
						Dalam memproses data akan memerlukan waktu lebih lama.
						<b>Harap Bersabar Menuggu.</b>
					</div>
					
					<div class="form-group row">

						<div class="col-lg-3">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($rombel as $data)
								<option {{  selectAktif($getrbl, encrypt_url($data->tgsnilaiKodeRombel) )  }} value="{{ encrypt_url($data->tgsnilaiKodeRombel) }}">
								{{ $data->tgsnilaiKelas }} 
								{{ substr($data->tgsnilaiKodeRombel ,2) }} | Angkatan {{ substr($data->tgsnilaiKodeRombel,0,2) }}
								</option>
								@endforeach

							</select>
						</div>
						{{-- <div class="col-lg-8">
							<select required data-placeholder="Pilih Tugas"  name="tugas" id="tugas"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getListTugas as $data )
								<option {{  selectAktif($gettugas, encrypt_url($data->el_tugas->tugasId))  }}  value="{{ encrypt_url($data->el_tugas->tugasId) }}">{{ $data->el_tugas->tugasJudul }}</option>
							@endforeach
							</select>
						</div> --}}
						
						
					
						{{-- bagian data --}}
						@if(!empty($_GET['rbl']))
						<div class="row" style="padding-top: 20px;">
							
							<div class="col-md-12">
								
								<table id="tabel" class="table table-striped table-bordered  " >
									<thead style="background-color: #05405a; color: white;">
										<tr>
											<th>USERNAME</th>
											<th>NAMA SISWA</th>
											<th>ROMBEL</th>
											@foreach ($dataTugas as $data)
												<th title="{{ $data['nama_tugas'] }}">{{ $data['tugas'] }}</th>
											@endforeach
											<th>TOTAL NILAI</th>
											<th>NILAI %</th>
											
										</tr>
									</thead>
									<tbody>
										<?php $totalNilai =0; $jmlTugas =0; ?>
										@foreach ($dataNilaiSiswa as $key => $data)
												<tr>
													<td>{{ $data['username'] }}</td>
													<td>{{ $data['nama_siswa'] }}</td>
													<td>
														{{ $rombelNilai->tgsnilaiKelas }} 
														{{ substr($rombelNilai->tgsnilaiKodeRombel ,2) }}
													</td>
														@foreach ($data['nilai'] as $key2 =>  $nilai)
															@foreach ($nilai as $nilai2)
															<?php 
															$jmlTugas++;
															$totalNilai += $nilai2;  ?>
																<td style="text-align: center">{{ $nilai2}}</td>
															@endforeach
														@endforeach
													<td style="text-align: center">{{ $totalNilai}}</td>
													<td style="text-align: center"><b>{{ ($totalNilai)/$jmlTugas}} %</b></td>
												</tr>
												<?php 
												if(@$data[$key+1]['username'] != $data['username'] ){
													$totalNilai =0;
													$jmlTugas=0;
												} 
												?>
											@endforeach
									</tbody>
								</table>
							</div>
							
						</div>
						@endif
						{{-- End bagian data --}}

					</div>
				</div>
			</div>
			
			
		</div>
		</form>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
</div>

<!-- /content area -->
@endsection
@push('js_atas')

<!-- Theme JS files -->

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
{{-- datetimepicker --}}
<script src="{{ asset('global_assets/js/plugins/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_checkboxes_radios.js') }}"></script>
{{-- modal --}}
<script src="{{ asset('global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<!-- /theme JS files -->
@endpush
@push('js_bawah')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
{{-- modal--}}
<script src="{{ asset('global_assets/js/demo_pages/components_modals.js') }}"></script>
@endpush
@push('jsku')
<script type="text/javascript">
$(document).ready(function() {
	$('#rbl').change(function(){
			var rbl = $(this).val();
			location="?rbl="+rbl;
		});
		

	//cek all
	$('#checkall').change(function () {
			$('.cekpilih').prop('checked',this.checked);
	});

	$('.ceksiswa').change(function () {
		if ($('.cekpilih:checked').length == $('.cekpilih').length){
			$('#checkall').prop('checked',true);
		}
		else {
			$('#checkall').prop('checked',false);
		}
	});
	$('.datepicker').datetimepicker({
			timepicker: false,
			format: 'd-m-Y'
	});
	var tabel = $('#tabel').DataTable({
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		"lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
		//dom: '<"datatable-header"fBl>',
		dom: 'Bfrtip',
		buttons: 
			{            
				buttons: 
				[
					{
						extend: 'copyHtml5',
						className: 'btn btn-light',
						title: 'REKAP NILAI TUGAS',
						//messageTop: 'Judul Tugas :  {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						// exportOptions: {
						// 	columns: [ 1, 2, 3 ]
						// }
					},
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						title: 'REKAP NILAI TUGAS',
						//messageTop: 'Judul Tugas :  {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						// exportOptions: {
						// 	columns: [ 1, 2, 3 ]
						// }
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i>',
						className: 'btn bg-blue btn-icon dropdown-toggle'
					},
					{
						extend: 'print',
						title: 'REKAP NILAI TUGAS',
						//messageTop: 'Judul Tugas {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						className: 'btn btn-light',
						// exportOptions: {
						// 	columns: [ 1, 2, 3 ]
						// }
					},
					// {
					//     extend: 'csv',
					 //     className: 'btn btn-light',
					// }            
			 ]
			},
		
	});


		

		
		$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'PUT',
				url:route,
				data:data_form.serialize(),
				beforeSend: function() {
					$("#pesanku").text("Proses ...!");
					$('.loader').show();
      	},
				success:function(respon){
					//console.log(respon);
					if(respon.success){
						$('.loader').hide();
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					setInterval(function(){ location.reload(true); }, 1000);
					}
					else{
						$('.loader').hide();
						new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
				},
				error: function (respon) {
					$('.loader').hide();
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
});
		
</script>
@endpush

