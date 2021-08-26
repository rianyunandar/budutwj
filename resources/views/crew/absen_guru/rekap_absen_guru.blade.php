@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }
if(!empty($_GET['skl'])){ 
	$url = 'crew/json-cetak-absen-guru?skl='.$getskl.'&bln='.$getbln.'&thn='.$getthn; 
	$url2 = 'crew/cetak-absen-guru?skl='.$getskl.'&bln='.$getbln.'&thn='.$getthn; 
} else{ $url='crew/json-cetak-absen-guru';}

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
						<div class="col-lg-4">
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">{{$skl->sklNama}}</option>
								@endforeach
							</select>
						</div>

					
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Tahun"  name="thn" id="thn"  class="form-control select" data-fouc>
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
			{{-- bagian data --}}
			@if(!empty($_GET['skl']))
			<div class="row mt-2">
				<div class="col-md-6">
					<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-primary"><i class="icon-printer"></i> Print Absen Bulan</button>
				</div>
			</div>
			<label class="text-muted">Pastikan Cetak melalui PC atau Laptop</label>
			<iframe id='loadframe' name='frameresult' src="{{ url($url2) }}" style='border:none;width:1px;height:1px;'></iframe>
			<div class="row">
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  ">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>NAMA</th>
								@for ($i=1; $i <= 31; $i++)
								<th>{{ $i }}</th>
								@endfor
								<td>TOTAL H</td>
								<td>TOTAL A</td>
								<td>TOTAL I</td>
								<td>TOTAL S</td>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			@endif
		
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
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js')}}"></script>

<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

<!-- pluginnya form select-->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<!-- pluginnya buat export -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_api.js') }}"></script>


@endpush
@push('js_atas2')

@endpush
@push('jsku')
<script type="text/javascript">
// Default initialization
$('.select').select2({ minimumResultsForSearch: Infinity });
// Select with search
$('.select-search').select2();


	$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var thn = $('#thn').val();
		var bln = $('#bln').val();
  	location="?skl="+skl+"&thn="+thn+"&bln="+bln+" ";
	});
	var tabel = $('#tabel').DataTable({
		
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan to filter...',
			lengthMenu: '<span>Tampil:</span> _MENU_ <span>Data</span>',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},

		processing: true,
	  serverSide: true,
		"lengthMenu": [[10, 50, 100, 150, 200, 300, -1], [10, 50, 100, 150, 200, 300, "All"]],

		ajax: '{{ url($url) }}',
		buttons: 
			{            
				buttons: 
				[
					{
						extend: 'copyHtml5',
						className: 'btn btn-light',
						title : 'REKAP ABSENSI KEHADIRAN GURU',
						exportOptions: {
							columns: [ 0, ':visible' ]
						}
					},
					{
						//excel
						extend: 'excelHtml5',
						className: 'btn btn-light',
						// messageTop: 'SMK BUDI UTOMO ',
						title : 'REKAP ABSENSI KEHADIRAN GURU',
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
		
		columns: [
			{ "data": "hgNamaFull" },
			{ "data": "tgl1" },{ "data": "tgl2" },{ "data": "tgl3" },{ "data": "tgl4" },{ "data": "tgl5" },
			{ "data": "tgl6" },{ "data": "tgl7" },{ "data": "tgl8" },{ "data": "tgl9" },{ "data": "tgl10" },

			{ "data": "tgl11" },{ "data": "tgl12" },{ "data": "tgl13" },{ "data": "tgl14" },{ "data": "tgl15" },
			{ "data": "tgl16" },{ "data": "tgl17" },{ "data": "tgl18" },{ "data": "tgl19" },{ "data": "tgl20" },

			{ "data": "tgl21" },{ "data": "tgl22" },{ "data": "tgl23" },{ "data": "tgl24" },{ "data": "tgl25" },
			{ "data": "tgl26" },{ "data": "tgl27" },{ "data": "tgl28" },{ "data": "tgl29" },{ "data": "tgl30" },

			{ "data": "tgl31" },

			{ "data": "HADIR" },
			{ "data": "ALPHA" },
			{ "data": "IZIN" },
			{ "data": "SAKIT" },

		],

		scrollX: true,
    scrollCollapse: true,
    fixedColumns: {
			leftColumns: 1,
		}
		
		
	});

</script>
@endpush

