@extends('master_guru')
@section('title')
@section('content')
<!-- Content area -->
<?php
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; } else{ $getrbl =''; }
if(!empty($_GET['mpl'])){ $getmapel =$_GET['mpl']; } else{ $getmapel =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; } else{ $getbln =''; }
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
								<option  {{ selectAktif($getmapel, encrypt_url($data->majdId)) }} value="{{ encrypt_url($data->majdId) }}">{{ $data->majdNama }} | {{ hariIndo($data->majdHari) }}</option>
							@endforeach
							</select>
						</div>
						
						<div class="col-lg-2">
							<select required data-placeholder="Pilih Bulan"  name="bln" id="bln"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach (getBulan() as $data )
								<option  {{ selectAktif($getbln, encrypt_url($data)) }} value="{{ encrypt_url($data) }}">{{ bulanIndo($data) }}</option>
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
			<iframe id='loadframe' name='frameresult' src="{{ url('guru/cetak-absen-mapel-detail?skl='.$getskl.'&rbl='.$getrbl.'&mapel='.$getmapel.'&bln='.$getbln) }}" style='border:none;width:1px;height:1px;'></iframe>
			{{-- bagian data --}}
			@if(!empty($_GET['mpl']))
			<div class="row">
				<div class="col-md-6">
					<button onclick="frames['frameresult'].print()" target="_blank"  class="btn btn-sm btn-info"><i class="fa fa-download"></i> Print Absen Bulan</button>
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


	

	
</script>
@endpush

