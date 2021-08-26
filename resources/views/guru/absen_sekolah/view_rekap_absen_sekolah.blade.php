@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; }else{ $getrbl =''; }
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }


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

		<div class="card-body">
			<div class="row ">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-lg-3">
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">{{$skl->sklNama}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-2">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getRombel as $rbl)
								<option {{ selectAktif($getrbl, encrypt_url($rbl->rblId)) }}   value="{{ encrypt_url($rbl->rblId) }}">{{$rbl->master_kelas->klsKode.' '.$rbl->rblNama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Tahun"  name="thn" id="thn"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getBulanTahunAbsen as $thn)
								<option {{ selectAktif($getthn, encrypt_url($thn->tahun))}}  value="{{ encrypt_url($thn->tahun) }}">{{$thn->tahun}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach (getBulan() as $bln)
								<option {{ selectAktif($getbln, encrypt_url($bln))  }} value="{{ encrypt_url($bln) }}">{{ bulanIndo($bln)}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
						<iframe id='loadframe' name='frameresult' src="{{ url('guru/cetak-rekap-absen-sekolah-guru?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&thn='.$getthn) }}" style='border:none;width:1px;height:1px;'></iframe>
						{{-- bagian data --}}
						@if(!empty($_GET['skl']))
						<div class="row">
							<div class="col-md-6">
								<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-info">
								<i class="icon-printer"></i> Print Absen Bulan</button><br>
								<label class="text-muted">Pastikan Cetak melalui PC atau Laptop</label>
								{{-- <a href="{{ url('crew/cetak-view-rekap-absen-finger?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&thn='.$getthn) }}" >asdasdsa</a> --}}
							</div>
						</div>
						@endif
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
						<table id="tabel2" class="table table-striped table-bordered" width="100" style="width: 100%">
							<thead style="background-color: #05405a; color: white;">
							<tr height=40px>
								<th width='2%' align=center>No</th>
								<th  width='25%'>NAMA</th>
							
								<th width='1%'>H</th>
								<th width='1%'>A</th>
								<!-- <th width='1%'>B</th> -->
								<th width='1%'>I</th>
								<th width='1%'>T</th>
								<th width='1%'>S</th>
								{{-- <th width='1%'>U</th>
								<th width='1%'>L</th>
								<th width='1%'>K</th> --}}
								<!-- <th width='4%'>%</th> -->
								</tr>
							</thead>
							<tbody>
								<?php $no=1; ?>
								@foreach ($absen as $data)
								<tr>
									<td>{{ $no++ }}</td></td>
									<td>{{ $data->user_siswa->ssaFirstName.' '.$data->user_siswa->ssaLastName }}</td> 
								
									<td align="center">{{ $data->HADIR }}</td>
									<td align="center">{{ $data->ALPHA }}</td>
									<td align="center">{{ $data->IZIN }}</td>
									<td align="center">{{ $data->TERLAMBAT }}</td>
									<td align="center">{{ $data->SAKIT }}</td>
									{{-- <td align="center">{{ $data->ULANGAN }}</td>
									<td align="center">{{ $data->LIBUR }}</td>
									<td align="center">{{ $data->KEGIATAN }}</td> --}}
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
	$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var rbl = $('#rbl').val();
		var thn = $('#thn').val();
		var bln = $('#bln').val();
  	location="?skl="+skl+"&rbl="+rbl+"&thn="+thn+"&bln="+bln+" ";
	});
		var tabel = $('#tabel2').DataTable({
	
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			emptyTable: "Data Tidak Ada ",
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
		
			scrollX: true,
			scrollY: '700px',
			scrollCollapse: true,
			fixedColumns: {
				leftColumns: 1,
			},
	order: [2, 'asc'],
});


 
</script>
@endpush

