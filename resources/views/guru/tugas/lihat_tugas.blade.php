@extends('master_guru')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['kelas'])){ $getkls =$_GET['kelas']; } else{ $getkls =''; }
if(!empty($_GET['mapel'])){ $getmapel =$_GET['mapel']; } else{ $getmapel =''; }
if(!empty($_GET['skl'])){ 
	$url = 'guru/json-tugas?skl='.$getskl.'&kelas='.$getkls.'&mapel='.$getmapel; 
} else{ $url='guru/json-tugas';}

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
			<div class="row pb-3">
				<div class="col-md-4">
					<div class="form-group">
						<label ><b>Sekolah</b></label>
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select " >
							<option></option>
							<option value="{{ encrypt_url('ALL') }}">ALL</option>
							@foreach ($getSekolah as $skl)
								<option {{ selectAktif(encrypt_url($skl->sklId),$getskl) }} value="{{ encrypt_url($skl->sklId) }}">{{ $skl->sklNama}}</option>
							@endforeach
							</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label><b>KELAS</b></label><br>
						<select required data-placeholder="Pilih Kelas"  name="kelas" id="kelas"  class="form-control select">
							<option value=""></option>
							@foreach ($getKelas as $data)
								<option value="{{ encrypt_url($data['klsKode']) }}">{{ $data['klsKode'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label><b>MAPEL</b></label><br>
							<select  required data-placeholder="Pilih Mapel"  name="mapel" id="mapel"  class="form-control select-search" data-fouc>
							</select>
					</div>
				</div>
				
			</div>
			
			<div class="row" id="jadwaltabel" style="">
				<div class="col-md-12">
					<div class="table-responsive">
					<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>AKSI</th>
								<th>JUDUL</th>
								<th>KI</th>
								<th>KD</th>
								{{-- <th>PER KE</th> --}}
								{{-- <th>ROMBEL</th> --}}
								{{-- <th>TERBIT</th> --}}
								<th>STATUS</th>
								<th>NAMA MAPEL</th>
								<th>GURU PENGAJAR</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					</div>
				</div>
			</div>	
	</div>
	<br><br><br>
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
	$('#kelas').change(function(){
			// var id=$(this).val();
			var skl=$('#skl').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				url : "{{ route('pilih.jadwal.guru') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Mapel</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].majdMapelKode+'>'+data[i].majdNama+'</option>';
					}
					$('#mapel').html(html);
				}
			});
		});
		$('#mapel').change(function(){
			var skl = $('#skl').val();
			var kelas = $('#kelas').val();
			var mapel = $(this).val();
			location="?skl="+skl+"&kelas="+kelas+"&mapel="+mapel;
		});

</script>

<script type="text/javascript">
	$(document).on('click','#delete',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");

		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Materi Ini ? Matei yang di hapus tidak bisa di kembalikan</h3><center> ',
			timeout: false,
			modal: true,
			layout: 'center',
			closeWith: 'button',
			type: 'confirm',
			buttons: [
			Noty.button('Cancel', 'btn btn-link', function () {
				notyConfirm.close();
			}),

			Noty.button('Hapus <i class="icon-trash ml-2"></i>', 'btn bg-danger ml-1', function () {
				$.ajax({
					type:'PUT',
					url:id+'/delete-tugas',
					data: { _token: token,id: id },
					success:function(respon){
						console.log(respon);
						if(respon.success){
							new Noty({
								theme: ' alert alert-success alert-styled-left p-0 bg-white',
								text: respon.success,
								type: 'success',
								progressBar: false,
								closeWith: ['button']
							}).show();
							tabel.ajax.reload();
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
				notyConfirm.close();
			},
			{id: 'button1', 'data-status': 'ok'}
			)
			]
		}).show();
	});

var tabel = $('#tabel').DataTable({
		processing: true,
		serverSide: true,
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		// dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		//autoWidth: true,
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
		{ "data": "aksi" },
		{ "data": "tugasJudul" },
		{ "data": "tugasKi" },
		{ "data": "tugasKd" },
		{"mRender": function ( data, type, row ) 
			{
				if(row.tugasTampil == 1){
					var btn ='<span class="badge badge-success">Aktif</span>';
				}
				else{
					var btn ='<span class="badge badge-danger">OFF</span>';
				}
				return btn;
			}
		},	
		{ "data": "tugasMapelNama" },
		{ "data": "guru" },

		],
		responsive: { details: { type: 'column' } },
		columnDefs: [
		// { width: "50%", targets: 2 },
		// { width: "50%", targets: 9 },
		{ className: 'control',orderable: false, targets:   0 },
	
		
		],
		order: [6, 'asc'],
	});

$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					console.log(respon);
					if(respon.save){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					setInterval(function(){ location.reload(true); }, 1000);
					}
					else{
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
$('#update').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'PUT',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					console.log(respon);
					if(respon.save){
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					tabel.ajax.reload();
					}
					else{
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

		

</script>
@endpush

