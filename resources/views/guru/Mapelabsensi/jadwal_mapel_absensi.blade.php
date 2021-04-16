@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->

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
			{{-- -------------------------------------------------- --}}
			<div class="row mb-2" style="" id="tambah2">
				<div class="col-md-12">
					<button id="tambah" class="btn btn-primary"><i class="icon-plus3"></i> Tambah Jadwal</button>
				</div>
			</div>
			<div class="row mb-2" id="kembali2" style="display: none;">
				<div class="col-md-12">
					<button id="kembali" class="btn btn-default"><i class="icon-arrow-left52"></i> Kembali</button>
				</div>
			</div>
			{{-- -------------------------------------------------- --}}
		
			<div class="row mb-2" id="tbmjadwal2" style="display: none;">
				<form id="insert" method="post" data-route="{{ route('insert.guru.jadwal.mapel') }}">
					{{ csrf_field() }}
					<div class="col-md-12">
						<fieldset>
							<div class="form-group row">
								<div class="col-lg-8">
									<label class="col-form-label">Sekolah</label>
									<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single skl" data-fouc>
									<option></option>
									@foreach ($getSekolah as $skl)
										<option value="{{ encrypt_url($skl->sklId)}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label class="col-form-label">Kelas</label>
									<select required data-placeholder="Pilih Kelas"  name="kelas" id="kelas"  class="form-control select-fixed-single" data-fouc>
										<option value=""></option>
										@foreach ($getKelas as $data)
											<option value="{{ $data['klsId'] }}">{{ $data['klsKode'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label class="col-form-label">Jurusan</label>
									<select required data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-lg-12">
									<label class="col-form-label">Rombel</label>
									<select style="width: 300px;" required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-12" style="width: 90%;">
									<label class="col-form-label">Mapel</label>
									<select  required data-placeholder="Pilih Mapel"  name="mapel" id="mapel"  class="form-control select-search" data-fouc>
									</select>
								</div>
							</div>
							<div class="form-group row">
									<div class="col-lg-8">
										<label class="col-form-label">Hari</label>
										<select id="hari" name="hari" class="form-control select-fixed-single" required data-placeholder="Pilih Nama Hari">
											<option></option>
											<option value="Monday">Senin</option>
											<option value="Tuesday">Selasa</option>
											<option value="Wednesday">Rabu</option>
											<option value="Thursday">Kamis</option>
											<option value="Friday">Jumat</option>
											<option value="Saturday">Sabtu</option>
											<option value="Sunday">Minggu</option>
										</select>
									</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label class="col-form-label">Mapel Jam Ke</label>
									<input type='number' name='mapeljamke' class='form-control' autocomplete='off' required='true' />
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label class="col-form-label">Jam Mulai</label>
									<input type='text' name='jamin' class='timer form-control' autocomplete='off' required='true' />
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label class="col-form-label">Jam Akhir</label>
									<input type='text' name='jamout' class='timer form-control' autocomplete='off' required='true' />
								</div>
							</div>
						
						</fieldset>
						<button id="tbmjadwal" class="btn btn-info">
							<i class="mi-save"></i> Tambah Jadwal
						</button>
					</div>
				</form>
			</div>
			<div class="row" id="jadwaltabel" style="">
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  datatable-responsive-column-controlled">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th>#</th>
								<th>AKSI</th>
								<th>NAMA MAPEL</th>
								<th>KODE SEKOLAH</th>
								<th>HARI</th>
								<th>JAM MULAI</th>
								<th>JAM AKHIR</th>
								<th>ROMBEL</th>
								<th>KELAS</th>
								<th>MAPEL JAM KE</th>
								<th>TAMPIL KE SISWA</th>
								<th>GURU PENGAJAR</th>
								<th>TAHUN AJARAN</th>
								<th>STATUS</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>	
	</div>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
</div>
<!-- Vertical form modal -->
	<div id="modal_form_vertical" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Jadwal</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<form id="update"  data-route="{{ route('guru.update.jadwal.mapel') }}">
					{{ csrf_field() }}
					<input type='hidden' id="erbl" name='erbl' />
					<div class="modal-body">
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Hari</label>
								<div class="col-lg-8">
									<input id="eid" name="eid" type="hidden">
									<select id="ehari" name="ehari" class="form-control select-fixed-single" required data-placeholder="Pilih Nama Hari">
										<option></option>
										<option value="Monday">Senin</option>
										<option value="Tuesday">Selasa</option>
										<option value="Wednesday">Rabu</option>
										<option value="Thursday">Kamis</option>
										<option value="Friday">Jumat</option>
										<option value="Saturday">Sabtu</option>
										<option value="Sunday">Minggu</option>
									</select>
								</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Mapel Jam Ke</label>
							<div class="col-lg-4">
								<input type='number' id="emapeljamke" name='emapeljamke' class='form-control' autocomplete='off' required='true' />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Jam Mulai</label>
							<div class="col-lg-4">
								<input type='text' id="ejamin" name='ejamin' class='timer form-control' autocomplete='off' required='true' />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Jam Akhir</label>
							<div class="col-lg-4">
								<input type='text' id="ejamout" name='ejamout' class='timer form-control' autocomplete='off' required='true' />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Jadwal Tampilkan Ke Siswa</label>
							<div class="col-lg-4">
								<select id="etampil" name="etampil" class="form-control select-fixed-single" required data-placeholder="Pilih Nama Hari">
									<option></option>
									<option value="1">TAMPILKAN</option>
									<option value="0">TIDAK</option>
								</select>
								
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Status Jadwal</label>
							<div class="col-lg-4">
								<select id="eaktif" name="eaktif" class="form-control select-fixed-single" required data-placeholder="Pilih Nama Hari">
									<option></option>
									<option value="1">AKTIF</option>
									<option value="0">TIDAK</option>
								</select>
								
							</div>
						</div>


					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn bg-primary">Update Jadwal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- /vertical form modal -->
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
	$("#tambah").click(function(){
		$('#tbmjadwal2').removeAttr('style');
		$('#kembali2').removeAttr('style');
		$("#tambah").css("display","none");
		$('#jadwaltabel').css("display","none");
		$('#tbmjadwal2').slideDown(1000);
	});
	$("#kembali2").click(function(){
		$("#tambah").removeAttr('style');
		$('#jadwaltabel').removeAttr('style');
		$('#kembali2').css("display","none");
		$('#tbmjadwal2').css("display","none");
		$('#tambah').slideDown(1000);
	});
</script>
<script type="text/javascript">
		$('.timer').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});
		$('#skl').change(function(){
			var id=$(this).val();
			$('#kelas').val("").trigger( "change" );
			$('#jrs').val("").trigger( "change" );
			$('#rbl').val("").trigger( "change" );
			
			//alert(id);
			$.ajax({
				url : "{{ route('pilih.jurusan') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Jurusan</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idjrs+'>'+data[i].slugjrs+'</option>';
					}
					$('#jrs').html(html);
				}
			});
			return false;
		});
		// $('.skl').change(function(){ //class skl untukg get mapel
		// 	var id=$(this).val();
		// 	var kelas=$('#kelas').val();
		// 	var jurusan=$('#jrs').val();
		// 	//alert(id);
		// 	$.ajax({
		// 		url : "{{ route('pilih.mapel') }}",
		// 		method : "POST",
		// 		data : {id: id,jrs:jurusan,kelas:kelas,_token: "{{ csrf_token() }}"},
		// 		async : false,
		// 		dataType : 'json',
		// 		success: function(data){
		// 			console.log(data);
		// 			var html = '<option value="">Pilih Mapel</option>';
		// 			var i;
		// 			for(i=0; i<data.length; i++){
		// 				html += '<option value='+data[i].kodemapel+'>'+data[i].namamapel+'</option>';
		// 			}
		// 			$('#mapel').html(html);
		// 		}
		// 	});
		// 	return false;
		// });

		$('#kelas').change(function(){
			$('#jrs').val("").trigger( "change" );
			$('#rbl').val("").trigger( "change" );
		});
		$('#jrs').change(function(){
			var id=$(this).val();
			var skl=$('#skl').val();
			var kelas=$('#kelas').val();
			$('#rbl').empty();
			$('#siswa').empty();
			$.ajax({
				url : "{{ route('pilih.rombel.kelas') }}",
				method : "POST",
				data : {id: id,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Rombel</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].koderbl+'>'+data[i].level+''+data[i].nmrbl+'</option>';
					}
					$('#rbl').html(html);
				}
			});
			$.ajax({
				url : "{{ route('pilih.mapel') }}",
				method : "POST",
				data : {skl: skl,jrs:id,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					console.log(data);
					var html = '<option value="">Pilih Mapel</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].kodemapel+'>'+data[i].namamapel+'</option>';
					}
					$('#mapel').html(html);
				}
			});

			return false;
		});
</script>
<script type="text/javascript">

		$(document).on('click', '.editjadwal', function() {
			var id = $(this).data('id');	
			var hari = $(this).data('hari');
			var jamin = $(this).data('jamin');
			var jamout = $(this).data('jamout');
			var jamke = $(this).data('jamke');
			var tampil = parseInt($(this).data('tampil'));
			var aktif = $(this).data('aktif');	 
			var rbl = $(this).data('rbl');	 

				$('#eid').val(id);
				$('#ejamin').val(jamin);
				$('#ejamout').val(jamout);
				$('#emapeljamke').val(jamke);
				$('#ehari').val(hari).change();
				$('#etampil').val(tampil).change();
				$('#eaktif').val(aktif).change();
				$('#erbl').val(rbl);

		});
	$(document).on('click','#delete',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");

		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Data Ini, Data Absen Juga Akan Di Hapus Semua ?</h3><center> ',
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
					url:id+'/delete-jadwal',
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
		//serverSide: true,
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		// dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		responsive: true,
		autoWidth: false,
		ajax: '{{ route('json.guru.jadwal.mapel') }}',
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
		{ "data": "majdNama" },
		{ "data": "sekolah" },
		{ "data": "hari" },
		{ "data": "majdJamMulai" },
		{ "data": "majdJamAkhir" },
		{ "data": "rombel" },
		{ "data": "majdKlsKode" },
		{ "data": "majdJamKe" },
		{ "data": "tampil" },
		{ "data": "namaguru" },
		{ "data": "majdTahunAjaran" },
		{ "data": "status" },

		],
		responsive: { details: { type: 'column' } },
		columnDefs: [
		{ width: 400, targets: 7 },
		{ className: 'control',orderable: false, targets:   0 },
	
		
		],
		order: [2, 'asc'],
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

