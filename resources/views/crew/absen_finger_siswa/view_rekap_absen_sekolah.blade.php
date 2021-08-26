@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }
if(!empty($_GET['rbl'])){ $getrbl =$_GET['rbl']; }else{ $getrbl =''; }
if(!empty($_GET['thn'])){ $getthn =$_GET['thn']; }else{ $getthn =''; }
if(!empty($_GET['bln'])){ $getbln =$_GET['bln']; }else{ $getbln =''; }
if(!empty($_GET['skl'])){ 
	$url = 'crew/json-view-rekap-absen-finger?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&thn='.$getthn; 
} else{ $url='crew/json-view-rekap-absen-finger';}

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
				<iframe id='loadframe' name='frameresult' src="{{ url('crew/cetak-view-rekap-absen-finger?skl='.$getskl.'&rbl='.$getrbl.'&bln='.$getbln.'&thn='.$getthn) }}" style='border:none;width:1px;height:1px;'></iframe>
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
@push('js_atas2')
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


  $(document).on('click','#deletejurusan',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");
		var cek = confirm('Apakah Anda Yakin Akan Menghapus Data Ini ?');
		if(cek==true){
			$.ajax({
				type:'delete',
				url:id+'/deletejurusan',
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
						if(respon.error){
							new Noty({
							theme: ' alert alert-danger alert-styled-left p-0',
							text: respon.error,
							type: 'error',
							progressBar: false,
							closeWith: ['button']
							}).show();
						}
					}
			});
		}
  });
</script>
@endpush

