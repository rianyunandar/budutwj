@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; } else{ $getrbl =''; }
if(!empty($_GET['mpl'])){ $getmapel =$_GET['mpl']; } else{ $getmapel =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; } else{ $getbln =''; }
if(!empty($_GET['skl'])){ 
	$url = 'guru/json-rekap-absen-mapel?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&mpl='.$getmapel; 
} else{ $url='guru/json-rekap-absen-mapel';}

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
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-lg-4">
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">{{$skl->sklNama}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-2">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
							</select>
						</div>
						<div class="col-lg-4">
							<select required data-placeholder="Pilih Mapel"  name="mapel" id="mapel"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getJadwal as $data )
								<option  value="{{ encrypt_url($data->majdId) }}">{{ $data->majdNama }} | {{ hariIndo($data->majdHari) }}</option>
							@endforeach
							</select>
						</div>
						
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach (getBulan() as $data )
								<option  value="{{ encrypt_url($data) }}">{{ bulanIndo($data) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2">
							<button id="cariabsen" class="btn btn-info">Cari Absensi</button>
							
						</div>
					</div>
				</div>
			</div>
			
			{{-- bagian data --}}
			@if(!empty($_GET['mpl']))
		
			<div class="row">
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  ">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th></th>
								<th>AKSI</th>
								<th>NAMA SISWA</th>
								<th>KELAS</th>
								<th>TANGGAL</th>
								<th>HARI</th>
								<th>JAM ABSEN</th>
								<th>STATUS</th>
								<th>CEK</th>
								
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			@endif
		</div>

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

	//jika sekolah sudah di pilih auto refres tetep terpilih kelas dan rombel
	@if(!empty($getskl))
	$("#rbl").empty();
	var id=$('#skl').val();
	var select = "<?= $getrbl ?>";
		$.ajax({
				url : "{{ route('pilih.rombel.skl') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					var dataRombel = []; <?php /*variabel array kosong*/  ?>
					<?php /*looping data */  ?>
					$.each(data, function(index, objek){
						if(objek.idrbl == select){ var selected="selected='selected'";  }else{ var selected=""; }
						var option = '<option data-idkls="'+objek.idrbl+'" '+selected+' value="'+objek.idrbl+'">'+objek.level+' '+objek.nmrbl+'</option>';
						 dataRombel.push(option);
					 });
					 <?php /*gabungkan data array denga push */  ?>
          $('#rbl').append('<option value="">Pilih Jurusan</option>'+dataRombel);
				}
			});
		@endif


});
$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var rbl = $('#rbl').val();
		var mapel = $('#mapel').val();
		var bln = $('#bln').val();
  	location="?skl="+skl+"&rbl="+rbl+"&bln="+bln+"&mpl="+mapel+" ";
	});

var tabel = $('#tabel').DataTable({
		processing: true,
		//serverSide: true,
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		autoWidth: false,
		ajax: '{{ url($url) }}',
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
		columns: [
		{ "data": "no" },
		{ "data": "username" },
		{ "data": "nama_siswa" },
		{ "data": "rombel" },
		{ "data": "tanggal" },
		{ "data": "hari" },
		{ "data": "abmpJamin" },
		{ "data": "status" },
		{ "data": "usercek" },
		],
		responsive: { details: { type: 'column' } },
		columnDefs: [
		// { width: 400, targets: 3 },
		{ className: 'control',orderable: false, targets:   0 },
		
		{ orderable: false, targets: [3] }
		],
		order: [4, 'desc'],
	});

	$('#skl').change(function(){
			var id=$(this).val();

			//alert(id);
			$.ajax({
				url : "{{ route('pilih.rombel.skl') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Jurusan</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idrbl+'>'+data[i].level+''+data[i].nmrbl+'</option>';
					}
					$('#rbl').html(html);
				}
			});
			return false;
		});
		

		$('#rbl').change(function(){
			var skl = $('#skl').val();
			var rbl = $(this).val();
			location="?skl="+skl+"&rbl="+rbl;
		});


		// $('#insert').submit(function(e){
		// 	var route = $(this).data('route');
		// 	var data_form = $(this);
		// 	e.preventDefault();
		// 	$.ajax({
		// 		type:'PUT',
		// 		url:route,
		// 		data:data_form.serialize(),
		// 		success:function(respon){
		// 			console.log(respon);
		// 			if(respon.success){
		// 				new Noty({
		// 				theme: ' alert alert-success alert-styled-left p-0 bg-white',
		// 				text: respon.success,
		// 				type: 'success',
		// 				progressBar: false,
		// 				closeWith: ['button']
		// 			}).show();
		// 			//setInterval(function(){ location.reload(true); }, 1000);
		// 			}
		// 			else{
		// 				new Noty({
		// 				theme: ' alert alert-danger alert-styled-left p-0',
		// 				text: respon.error,
		// 				type: 'error',
		// 				progressBar: false,
		// 				closeWith: ['button']
		// 				}).show();
		// 			}
		// 		},
		// 		error: function (respon) {
		// 			new Noty({
		// 				theme: ' alert alert-danger alert-styled-left p-0',
		// 				text: respon.error,
		// 				type: 'error',
		// 				progressBar: false,
		// 				closeWith: ['button']
		// 				}).show();
		// 		 }
								
		// 	});
		// });

	
</script>
@endpush

