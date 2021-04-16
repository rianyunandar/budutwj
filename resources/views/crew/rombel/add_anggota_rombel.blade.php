@extends('master')
@section('titile', 'Tambah Jurusan')
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
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-3">
						<select required data-placeholder="Pilih Sekolah Dahulu"  name="rbl" id="skl"  class="form-control select-fixed-single" data-fouc>
							<option></option>
							@foreach ($getSekolah as $skl)
							<option value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
							@endforeach
						</select>
						</div>
						<div class="col-md-3">
						<select required data-placeholder="Pilih Jurusan Dahulu"  name="rbl" id="jrs"  class="form-control select-fixed-single" data-fouc>
							<option></option>
							@foreach ($getJurusan as $jrs)
							<option value="{{ $jrs->jrsId}}">{{ $jrs->jrsNama}}</option>
							@endforeach
						</select>
						</div>
						<div class="col-md-3">
						<select required data-placeholder="Pilih Rombol Dahulu"  name="rbl" id="rbl"  class="form-control select-fixed-single" data-fouc>
							<option></option>
							@foreach ($getRombel as $rbl)
							<option value="{{ $rbl->rblId}}">{{ $rbl->master_kelas->klsKode.' '.$rbl->rblNama}}</option>
							@endforeach
						</select>
						</div>
					</div>
					<button id="rombel" class="btn btn-primary">Tambah Siswa Ke Rombel</button>
				</div>

			</div>
			<legend><center><b style="font-size: 20px;">Daftar Siswa Belum Ada Rombel<br>Silahkan Pilih Siswa</b></center></legend>
			<div id='tablereset' class='table-responsive'>
				<table id="tabel" class="table table-striped table-bordered">
					<thead style="background-color: #05405a; color: white;">
						<tr>
							<th><input type="checkbox" id='ceksemua'></th>
							<th>NO</th>
							<th>ID FINGER</th>
							<th>NAMA SISWA</th>
							<th>JURUSAN</th>
							<th>KODE SEKOLAH</th>
							<th>TAHUN ANGKATAN</th>
						</tr>
					</thead>
					<tbody>
						<?php $no=1; ?>
						@if($siswaNoRombel != null)
						@foreach ($siswaNoRombel as $siswa)
							<tr>
								<td><input type="checkbox" class="cekpilih " name='cekpilih[]' id='cekpilih-$no' value="{{$siswa->ssaUsername}}"></td>
								<td>{{ $no++}}</td>
								<td>{{ $siswa->ssaUsername}}</td>
								<td>{{ $siswa->ssaFirstName.' '.$siswa->ssaLastName}}</td>
								<td>{{ $siswa->master_jurusan->jrsSlag}}</td>
								<td>{{ $siswa->master_sekolah->sklKode}}</td>
								<td></td>
							</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<!-- pluginnya -->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
{{-- chekboss --}}
	<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
$(document).ready(function() {

	//cekall
	$("#ceksemua").change(function(){
		if(this.checked){
			$(".cekpilih").each(function(){
				this.checked=true;
			})              
		}else{
			$(".cekpilih").each(function(){
				this.checked=false;
			})              
		}
	});
	$(".cekpilih").click(function () {
		if ($(this).is(":checked")){
			var isAllChecked = 0;
			$(".cekpilih").each(function(){
				if(!this.checked)
					isAllChecked = 1;
			})              
			if(isAllChecked == 0){ $("#ceksemua").prop("checked", true); }     
		}else {
			$("#ceksemua").prop("checked", false);
		}
	});
   //cekall
	
	var tabel = $('#tabel').DataTable( {
        "order": [[ 2, "asc" ]]
    });
	
	$("#rombel").click(function() {
		var token = $("meta[name='csrf-token']").attr("content");
		var skl = $("#skl").val();
		var jrs = $("#jrs").val();
		var rbl = $("#rbl").val();

		id_array = new Array();
		i = 0;
		$("input.cekpilih:checked").each(function() {
			id_array[i] = $(this).val();
			i++;
		});
		//console.log(id_array);
		$.ajax({
			url: "{{ route('insert.anggota.rombel') }}",
			//data: "id=" + id_array,
			data: { 
				_token: token,
				id: id_array,
				skl:skl,
				jrs:jrs,
				rbl:rbl
			},
			type: "POST",
			success: function(respon) {
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
			}
		});
		return false;
	});
});	
</script>
@endpush

