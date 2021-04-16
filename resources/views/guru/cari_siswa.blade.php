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
			<div class="row mb-2">
				<div class="col-md-12">
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Sekolah</label>
							<div class="col-lg-9">
								<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
									<option value="{{ encrypt_url($skl->sklId)}}">{{ $skl->sklNama}}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Jurusan</label>
							<div class="col-lg-9">
								<select required data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Rombel</label>
							<div class="col-lg-5">
								<select required data-placeholder="Pilih Rombel"  name="rbl" id="rbl"  class="form-control select-search" data-fouc>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Siswa</label>
							<div class="col-lg-7">
								<select required data-placeholder="Pilih Siswa"  name="siswa" id="siswa"  class="form-control select-search" data-fouc>
								</select>
							</div>
						</div>
					</fieldset>
					<button id="cariabsen" class="btn btn-info"><i class="icon-search4"></i> Cari Data</button>
				</div>
			</div>
			
			@if(!empty($_GET['siswa']))
		
			{{-- data siswa lengkap --}}
		
				<div class="row "  >
					
						<div class="col-xl-3 col-sm-6">
							<div class="card">
								<div class="card-img-actions mx-1 mt-1" id="fotoprofile">
									<img class="card-img img-fluid " src="<?php echo GetFotoProfileSiswaGuru($datasiswa->ssaFotoProfile).'?date='.time(); ?>" alt="{{ FullNamaSiswa() }}">
									
								</div>
								<?php if($datasiswa->ssaIsActive==1){ $status ="<span  style='font-size: 2em' class='badge badge-success'>AKTIF</span>"; }
								else{ $status ="<span  class='badge badge-danger'>TIDAK AKTIF</span>"; } ?>
								<div class="col-md-12 pt-3 pb-4" style='text-align: center; '>
									{!! $status !!}
									<br><br>
									<table style="width: 100%; font-size: 15px;">
										<tr>
											<td ><b>NISN</b></td>
											<td>:</td>
											<td style="text-align: left; color:red ;">{{ $datasiswa->profile_siswa->psNisn }}</td>
										</tr>
										<tr>
											<td ><b>NIS</b></td>
											<td>:</td>
											<td style="text-align: left; color:red; ">{{ $datasiswa->profile_siswa->psNis }}</td>
										</tr>
										
									</table>

									{{-- <ul class="list-inline list-inline-condensed mt-3 mb-0">
										<li class="list-inline-item"><a href="#" class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round">
											<i class="icon-google-drive"></i></a>
										</li>
										<li class="list-inline-item"><a href="#" class="btn btn-outline bg-info btn-icon text-info border-info border-2 rounded-round">
											<i class="icon-twitter"></i></a>
										</li>
										<li class="list-inline-item"><a href="#" class="btn btn-outline bg-grey-800 btn-icon text-grey-800 border-grey-800 border-2 rounded-round">
											<i class="icon-github"></i></a>
										</li>
									</ul> --}}
								</div>
								
							</div>
						</div>
						<div class="col-xl-9 col-sm-6">
							<!-- Account settings -->
							<div class="card">
								<div class="card-header header-elements-inline">
									<h5 class="card-title"><b>INFORMASI DATA SISWA</b></h5>
									<div class="header-elements">
										<div class="list-icons">
											<a class="list-icons-item" data-action="collapse"></a>
											<a class="list-icons-item" data-action="reload"></a>
										</div>
									</div>
								</div>
								<div class="card-body">
										<div class="form-group">
											<div class="row">
												<div class="col-md-3">
													<label><b>Username</b></label>
													<input disabled="disabled"  value="{{ $datasiswa->ssaUsername }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Agama</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaAgama  }}" type="text" class="form-control">
												</div>
												<div class="col-md-3">
													<label>Nama Depan</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaFirstName  }}" type="text" class="form-control">
												</div>
												<div class="col-md-4">
													<label>Nama Belakang</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->ssaLastName  }}" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-2">
													<label>NO HP</label>
													<input disabled="disabled"  value="{{ $datasiswa->ssaHp }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>NO WA</label>
													@if(empty($datasiswa->ssaWa))
													<input disabled name="namabelakang" type="text"  class="form-control">
													@else 
													<a class="form-control" style="color: blue" target="_blank" href="https://wa.me/{{ $datasiswa->ssaWa }}" >{{ $datasiswa->ssaWa }}</a>
													@endif
												</div>
												
												<div class="col-md-4">
													<label>Transportasi</label>
													<?php if(!empty($datasiswa->profile_siswa->psTransport)){ $transpot= $datasiswa->profile_siswa->transportasi->trsNama; }else{ $transpot="";} ?>
													<input disabled name="namabelakang" type="text" value="{{  $transpot  }}" class="form-control">
												</div>
												<div class="col-md-4">
													<label>Sekolah</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->master_sekolah->sklNama  }}" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													<label>Jurusan</label>
													<input disabled="disabled"  value="{{ $datasiswa->master_jurusan->jrsNama }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Rombel</label>
													<input disabled="disabled"  value="{{ $datasiswa->anggota_rombel->master_rombel->master_kelas->klsNama.' '.$datasiswa->anggota_rombel->master_rombel->rblNama }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Tahun Angkatan</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaTahunAngkata  }}" type="text" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label>ASAL SMP</label>
													<?php 
													if(empty($datasiswa->profile_siswa->psAsalSmp)){
														$smp = '-';
													}
													else{
														$smp = $datasiswa->profile_siswa->master_smp->smpNama;
													} 
													?>  
													
													<input disabled name="smp" value="{{ $smp }}" type="text" class="form-control">
												</div>
												<div class="col-md-6">
													<label>Prakerind</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->ssaPrakerind  }}" class="form-control">
												</div>
											</div>
										</div>
								</div>
							</div>
							<!-- /account settings -->
						</div>
						{{-- datd profile siswa --}}
						<div class="col-xl-12 col-sm-6">
							<div class="card">
								<div class="card-header header-elements-inline">
								</div>
								<div class="card-body">
									
										<div class="row">
											<div class="col-md-3">
												<div class="form-group">
													<label class="cek">TEMPAT LAHIR</label>
													<input disabled value="{{$datasiswa->profile_siswa->psTpl}}" required type="text" name="tpl" placeholder="Tempat Lahir" class="form-control">
												</div>
											</div>
					
											<div class="col-md-3">
												<div class="form-group">
													<label class="cek">TANGGAL LAHIR</label>
													<input disabled type="date" name="tgl" value="{{$datasiswa->profile_siswa->psTgl}}" placeholder="Tanggal" class="form-control">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label class="cek">ALAMAT</label>
													<textarea disabled name="alamatsiswa" rows="1" cols="1" placeholder="Alamat " class="form-control">{{$datasiswa->profile_siswa->psAlamat}}</textarea>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label class="cek">JARAK KE SEKOLAH</label>
													<input disabled value="{{$datasiswa->profile_siswa->psJarak}}" type="text" name="jarak" class="form-control" placeholder="Jarak Dari Rumah Ke Sekolah">
												</div>
											</div>
										</div>

									<div class="row">
										<div class="col-md-1">
											<div class="form-group">
												<label class="cek">RT</label>
												<input disabled value="{{$datasiswa->profile_siswa->psRt}}" type="text" name="rt" class="form-control" placeholder="NO RT">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<label class="cek">RW</label>
												<input disabled value="{{$datasiswa->profile_siswa->psRw}}" type="text" name="rw" class="form-control" placeholder="NO RW">
											</div>
										</div>
				
										<div class="col-md-3">
											<div class="form-group">
												<label class="cek">DESA</label>
												<input disabled value="{{$datasiswa->profile_siswa->psDesa}}" type="text" name="desa" class="form-control" placeholder="Nama Desa">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="cek">KECAMATAN</label>
												<input disabled value="{{$datasiswa->profile_siswa->psKecamatan}}" type="text" name="keca" class="form-control" placeholder="Kecamatan">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">KABUPATEN</label>
												<input disabled value="{{$datasiswa->profile_siswa->psKabupaten}}" type="text" name="kabut" class="form-control" placeholder="Kabupaten">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">PROVINSI</label>
												<input disabled value="{{$datasiswa->profile_siswa->psProvinsi}}" type="text" name="provinsi" class="form-control" placeholder="Provinsi">
											</div>
										</div>
				
									</div>
		
									<div class="row">
											<div class="col-md-2">
												<div class="form-group">
													<label class="cek">NO NIK </label>
													<input disabled value="{{$datasiswa->profile_siswa->psNik}}" type="text" name="nik" class="form-control" placeholder="No NIK">
												</div>
											</div>
		
											<div class="col-md-2">
												<div class="form-group">
													<label class="cek">NO KK </label>
													<input disabled value="{{$datasiswa->profile_siswa->psNoKK}}" type="text" name="kk" class="form-control" placeholder="No KK">
												</div>
											</div>
											<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO KKS</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNoKKS}}" type="text" name="nokks" class="form-control" placeholder="NO KKS">
											</div>
											</div>
				
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO PKH</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNoPKH}}" type="text" name="nopkh" class="form-control" placeholder="No PKH">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO KIP</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNoKip}}" type="text" name="nokip" class="form-control" placeholder="NO KIP">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Hobi</label>
												<textarea disabled name="alamatsiswa" rows="1" cols="1" placeholder="Hobi" class="form-control">{{$datasiswa->profile_siswa->psHobi}}</textarea>
											</div>
										</div>
									</div>
									<div class="content-divider text-muted"><span class="px-2">Data Ayah</span></div>
									{{-- data ayah --}}
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Nama Ayah </label>
												<input disabled value="{{$datasiswa->profile_siswa->psNamaAyah}}" type="text" name="nik" class="form-control" >
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NIK Ayah</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNikAyah}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">No Hp Ayah</label>
											<input disabled value="{{$datasiswa->profile_siswa->psHpAyah}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
			
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO Wa Ayah</label>
												<input disabled value="{{$datasiswa->profile_siswa->psWaAyah}}" type="text" name="nopkh" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tempat Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTplAyah}}" type="text" name="nokip" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tanggal Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTglAyah}}" type="date" name="nokip" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Pendidiakn Ayah </label>
												<input disabled value="{{$datasiswa->profile_siswa->psPendidikanAyah}}" type="text" name="nik" class="form-control" >
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Penghasilan Ayah</label>
												<input disabled value="{{$datasiswa->profile_siswa->psPekerjaanAyah}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">Pekerjaan Ayah</label>
											<input disabled value="{{$datasiswa->profile_siswa->psPenghasilanAyah}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
									</div>
									<div class="content-divider text-muted"><span class="px-2">Data Ibu</span></div>
									{{-- data ibu --}}
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Nama Ibu </label>
												<input disabled value="{{$datasiswa->profile_siswa->psNamaIbu}}" type="text" name="nik" class="form-control">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NIK Ibu</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNikIbu}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">No Hp Ibu</label>
											<input disabled value="{{$datasiswa->profile_siswa->psHpIbu}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
			
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO Wa Ibu</label>
												<input disabled value="{{$datasiswa->profile_siswa->psWaIbu}}" type="text" name="nopkh" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tempat Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTplIbu}}" type="text" name="nokip" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tanggal Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTglIbu}}" type="date" name="nokip" class="form-control" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Pendidiakn Ibu </label>
												<input disabled value="{{$datasiswa->profile_siswa->psPendidikanIbu}}" type="text" name="nik" class="form-control" placeholder="No NIK">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Penghasilan Ibu</label>
												<input disabled value="{{$datasiswa->profile_siswa->psPekerjaanIbu}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">Pekerjaan Ibu</label>
											<input disabled value="{{$datasiswa->profile_siswa->psPenghasilanIbu}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
									</div>
									<div class="content-divider text-muted"><span class="px-2">Data Wali</span></div>
									{{-- data Wali --}}
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Nama Wali </label>
												<input disabled value="{{$datasiswa->profile_siswa->psNamaWali}}" type="text" name="nik" class="form-control">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NIK Wali</label>
												<input disabled value="{{$datasiswa->profile_siswa->psNikWali}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">No Hp Wali</label>
											<input disabled value="{{$datasiswa->profile_siswa->psHpWali}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
			
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">NO Wa Wali</label>
												<input disabled value="{{$datasiswa->profile_siswa->psWaWali}}" type="text" name="nopkh" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tempat Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTplWali}}" type="text" name="nokip" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Tanggal Lahir</label>
												<input disabled value="{{$datasiswa->profile_siswa->psTglWali}}" type="date" name="nokip" class="form-control" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Pendidiakn Wali </label>
												<input disabled value="{{$datasiswa->profile_siswa->psPendidikanWali}}" type="text" name="nik" class="form-control" placeholder="No NIK">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label class="cek">Penghasilan Wali</label>
												<input disabled value="{{$datasiswa->profile_siswa->psPekerjaanWali}}" type="text" name="kk" class="form-control" >
											</div>
										</div>
										<div class="col-md-2">
										<div class="form-group">
											<label class="cek">Pekerjaan Wali</label>
											<input disabled value="{{$datasiswa->profile_siswa->psPenghasilanWali}}" type="text" name="nokks" class="form-control" >
										</div>
										</div>
									</div>
								
							</div>
						</div>
					
						</div>
						<br><br>
				
				</div>	
			@endif
		
		
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
$('#skl').change(function(){
			var id=$(this).val();
			$('#jrs').empty();
			$('#rbl').empty();
			$('#siswa').empty();
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
		$('#jrs').change(function(){
			var id=$(this).val();
			$('#rbl').empty();
			$('#siswa').empty();
			$.ajax({
				url : "{{ route('pilih.rombel') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Rombel</option>';
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
			var id=$(this).val();
			$('#siswa').empty();
			$.ajax({
				url : "{{ route('pilih.siswa') }}",
				method : "POST",
				data : {id: id,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '<option value="">Pilih Siswa</option>';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].idsiswa+'>'+data[i].namasiswa+'</option>';
					}

					$('#siswa').html(html);
				}
			});
			return false;
		});

	$("#cariabsen").click(function(){
		var skl = $('#skl').val();
		var rbl = $('#rbl').val();
		var jrs = $('#jrs').val();
		var siswa = $('#siswa').val();
		if(skl==''){
			alert('Pilih Sekolah');
		}
		else if(rbl==''){
			alert('Pilih Rombel');
		}
		else if(jrs==''){
			alert('Pilih Jurusan');
		}
		else{
			
			location="?siswa="+siswa+" ";
		}
		
  	
	});


  
</script>
@endpush

