@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 

if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; } else{ $getrbl =''; }
if(!empty($_GET['tugas'])){ $gettugas =$_GET['tugas']; } else{ $gettugas =''; }

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
						Pada menu tambah nilai tugas, guru dapat memilih tugas berdasarkan rombel serta memberikan nilai pada tugas siswa <br>
						pada daftar nilai siswa yang tampil di bawah dengan memberikan nilai dan <b>CEKLIS SISWA</b> yang akan di berikan nilai kemudian klik tombol simpan nilai <br>
						Siswa yang di ceklis yang akan di proses simpan oleh sistem.
						<br>
						1. Pastikan semua siswa mendapat nilai minimal 0 dan maksimal 100<br>
						2. Nilai berupa puluhan 
					</div>
					
					<div class="form-group row">
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-search" data-fouc>
								<option></option>
								{{-- <option value="{{ encrypt_url('ALL') }}">ALL</option> --}}
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">SMKS {{$skl->sklKode}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-lg-2">
							<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
							</select>
						</div>
						<div class="col-lg-8">
							<select required data-placeholder="Pilih Tugas"  name="tugas" id="tugas"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getListTugas as $data )
								<option {{  selectAktif($gettugas, encrypt_url($data->el_tugas->tugasId))  }}  value="{{ encrypt_url($data->el_tugas->tugasId) }}">{{ $data->el_tugas->tugasJudul }}</option>
							@endforeach
							</select>
						</div>
						
						
					
						{{-- bagian data --}}
						@if(!empty($_GET['tugas']))
						<div class="row" style="padding-top: 20px;">
							
							<div class="col-md-12">
								<h2 style="text-align: center" >Silhakan Pilih Siswa Yang Akan di Beri Penilaian</h2>
								<span ><center> {{ !empty($getTugas) ? $getTugas->mapel->mapelNama : '' }} </center></span>
								<span ><center> {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }} </center></span>
								<table id="tabel" class="table table-striped table-bordered  " >
									<thead style="background-color: #05405a; color: white;">
										<tr>
											<th> ALL<input type="checkbox" id='checkall' class="form-check-input-styled-success" ></th>
											<th style="width: 50%" >NAMA SISWA</th>
											<th style="width: 20%">USERNAME SISWA</th>
											<th style="width: 15%">NILAI</th>
											<th style="width: 15%" >ISI NILAI</th>
										</tr>
									</thead>
									<tbody>
										<?php $no=1; ?>
										@foreach ($getSiswa as $data)
												<tr>
													<td><input value="{{ $data['username']}}" type="checkbox" id="{{ $no++ }}" name="cekpilih[]" class="cekpilih" ></td>
													<td>{{ $data['namasiswa']}}</td>
													<td>{{ $data['username'] }}</td>
													<td>{{ $data['nilai'] }}</td>
													<td> 
														<input value="{{ $data['nilai'] }}" id="nilai[{{ $data['username'] }}]" name="nilai[{{ $data['username'] }}]" class="form-control" type="number" >
													</td>
												</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="col-lg-2 mb-2">
								<button type="submit" class="btn btn-info">SIMPAN NILAI</button>
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
						title: 'TUGAS {{ !empty($getTugas) ? $getTugas->mapel->mapelNama : '' }}',
						messageTop: 'Judul Tugas :  {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						exportOptions: {
							columns: [ 1, 2, 3 ]
						}
					},
					{
						extend: 'excelHtml5',
						className: 'btn btn-light',
						title: 'TUGAS {{ !empty($getTugas) ? $getTugas->mapel->mapelNama : '' }}',
						messageTop: 'Judul Tugas :  {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						exportOptions: {
							columns: [ 1, 2, 3 ]
						}
					},
					{
						extend: 'colvis',
						text: '<i class="icon-three-bars"></i>',
						className: 'btn bg-blue btn-icon dropdown-toggle'
					},
					{
						extend: 'print',
						title: 'TUGAS {{ !empty($getTugas) ? $getTugas->mapel->mapelNama : '' }}',
						messageTop: 'Judul Tugas {{ !empty($getTugas) ? $getTugas->tugasJudul : '' }}',
						className: 'btn btn-light',
						exportOptions: {
							columns: [ 1, 2, 3 ]
						}
					},
					// {
					//     extend: 'csv',
					 //     className: 'btn btn-light',
					// }            
			 ]
			},
		
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
          $('#rbl').append('<option value="">Pilih Rombel</option>'+dataRombel);
				}
			});
		@endif


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
					var html = '<option value="">Pilih Rombel</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idrbl+'>'+data[i].level+' '+data[i].nmrbl+'</option>';
					}
					$('#rbl').html(html);
				}
			});
			return false;
		});
		

		$('#tugas').change(function(){
			var skl = $('#skl').val();
			var rbl = $("#rbl").val();
			var tugas = $(this).val();
			location="?skl="+skl+"&rbl="+rbl+"&tugas="+tugas;
		});
		$('#rbl').change(function(){
			var skl = $('#skl').val();
			var rbl = $(this).val();
			location="?skl="+skl+"&rbl="+rbl;
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
		
</script>
@endpush

