@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; } else{ $getrbl =''; }
// if(!empty($_GET['mapel'])){ $getmapel =$_GET['mapel']; } else{ $getmapel =''; }
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
		<form id="insert"  data-route="{{ route('insert.mapel.absen.manual.guru') }}">
			{{ csrf_field() }}
			
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
								<option  value="{{ $data->majdId }}">{{ $data->majdNama }} | {{ hariIndo($data->majdHari) }}</option>
							@endforeach
							</select>
						</div>
						
						<div class="col-lg-2">
							<input class="form-control datepicker" name="tgl" id="tgl" type="text" value="{{ date("d-M-Y") }}">
						</div>
						
					</div>
				</div>
			</div>
			
			{{-- bagian data --}}
			@if(!empty($_GET['rbl']))
			<div class="row">
				<div class="col-lg-2 mb-2">
					<button type="submit" class="btn btn-info">Klik Untuk Absen Siswa</button>
				</div>
				<div class="col-md-12">
					<table id="tabel" class="table table-striped table-bordered  ">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th> ALL<input type="checkbox" id='checkall' class="form-check-input-styled-success" ></th>
								<th>NAMA SISWA</th>
								<th>USERNAME SISWA</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=1; ?>
							@foreach ($getSiswa as $data)
									<tr>
										<td><input value="{{ $data->ssaUsername }}" type="checkbox" id="{{ $no++ }}" name="cekpilih[]" class="cekpilih" ></td>
										<td>{{ $data->ssaFirstName.' '.$data->ssaLastName }}</td>
										<td>{{ $data->ssaUsername }}</td>
									</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			@endif
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
var tabel = $('#tabel').DataTable({
		language: {
			search: '<span>Cari:</span> _INPUT_',
			searchPlaceholder: 'Ketikan Di Sini',
			lengthMenu: '<span>Tampil:</span> _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
		},
		"lengthMenu": [ [50, 100, -1], [50, 100, "All"] ],
		
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
					console.log(respon);
					if(respon.success){
						$('.loader').hide();
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					//setInterval(function(){ location.reload(true); }, 1000);
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
		/*
		$("#btnresetlogin").click(function() {
				id_array = new Array();
				i = 0;
				$("input.cekpilih:checked").each(function() {
					id_array[i] = $(this).val();
					i++;
				});
				var id = "guru_id";
				$.ajax({
					url: "hapus_all.php",
					data: "id="+id+"&kode=" + id_array,
					type: "POST",
					success: function(respon) {
						console.log(id_array);
						console.log(respon);
						if (respon == 1) {
							$("input.cekpilih:checked").each(function() {
								$(this).parent().parent().remove('.cekpilih').animate({
									opacity: "hide"
								}, "slow");
							})
						}
					}
				});
				return false;
			})
			*/
</script>
@endpush

