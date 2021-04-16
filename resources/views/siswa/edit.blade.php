@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	<style type="text/css">
		.cek{ font-weight: bold; color: #00bcd4;  }
	</style>
	<?php
	$blok= Setting()->setSiswaEdit;
	if($blok == 0){
		$blokedit = "disabled='disabled';"; 
		$textedit="Status Edit Profile Data di Nonaktifkan";
		$warnatext="text-danger";
	}
	else{
		$warnatext="text-success";
		$textedit="Status Edit Profile Aktif";
		$blokedit = "";
	}
	
	?>
	<!-- 2 columns form -->
	<div class="card">
		<div class="card-header header-elements-inline">
			<h5 class="card-title">{{ $label}}</h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>
		
		<div class="card-body">
			<span class="{{ $warnatext }}"><b>{{ $textedit }}</b></span>
			<form data-edit="{{ $blok }}" id="editsiswa" data-route="{{ route('update.data.siswa') }}" class="wizard-form steps-enable-all" method="POST" data-fouc>
				{{ csrf_field() }}
<!-- Data Siswa ----------------------------------------------->
				<h6>DATA SISWA</h6>
				<fieldset >
					<div class="row">
						{{-- <div class="col-md-3">
							<div class="form-group">
								<label class="cek">USERNAME</label>
								
							</div>
						</div> --}}
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NAMA DEPAN</label>
								<input value="{{ $siswa->ssaUsername }}" type="hidden" name="ssaUsername" class="form-control" placeholder="Username">
								<input {{ $blokedit }} value="{{ $siswa->ssaFirstName }}" required type="text" name="firstname" class="form-control" placeholder="Nama Depan Siswa">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NAMA BELAKANG</label>
								<input {{ $blokedit }} value="{{ $siswa->ssaLastName }}" required type="text" name="lastname" class="form-control" placeholder="Nama Belakang Siswa">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">JENIS KELAMIN</label>
								<select {{ $blokedit }} required  name="jsk"  class="form-control form-control-select2" data-fouc data-placeholder="Pilih Jenis Kelamin">
                  <option></option>
                  <option {{selectAktif($siswa->profile_siswa->psJsk,'L')}} value="L">Laki-Laki</option>
                  <option {{selectAktif($siswa->profile_siswa->psJsk,'P')}} value="P">Perempuan</option>
                </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">EMAIL</label>
								<input {{ $blokedit }} value="{{ $siswa->ssaEmail }}" type="email" name="email" class="form-control" placeholder="mryes@gamil.com">
							</div>
						</div>

					</div>
					{{-- <div class="content-divider text-muted"><span class="px-2"><i class="icon-circle-down2"></i></span></div> --}}
					<div class="row">
						
						<div class="col-md-2">
              <div class="form-group">
                <label class="cek">AGAMA</label>
                <select {{ $blokedit }} required  name="agama"  class="form-control form-control-select2" data-fouc data-placeholder="Pilih Agama">
                  <option></option>
                  @foreach ($getAgama as $agama)
                  <option {{ selectAktif($siswa->ssaAgama,$agama->agmKode) }} value="{{$agama->agmKode}}">{{$agama->agmNama}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
							<div class="form-group">
								<label class="cek">NO HP</label>
								<input {{ $blokedit }} value="{{$siswa->ssaHp}}" type="text" name="hpsiswa" class="form-control" placeholder="No Hp Siswa">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">No HP WA</label>
								<input {{ $blokedit }} value="{{$siswa->ssaWa}}" type="text" name="wasiswa" class="form-control" placeholder="Masukan No HP jika sama">
								
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">HOBI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psHobi}}" type="text" name="hobi" class="form-control" placeholder="Hobi">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">NIS </label>
								<input disabled value="{{$siswa->profile_siswa->psNis}}" type="text" name="nis" class="form-control" placeholder="Otomatis Dari Sistem">
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">NISN</label>
								<input disabled value="{{$siswa->profile_siswa->psNisn}}" type="text" name="nisn" class="form-control" placeholder="Otomatis Dari Sistem">
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="cek">ALAT TRANSPORTASI</label>
								
								<select {{ $blokedit }} required data-placeholder="Pilih Tranportasi"  name="transpot" id="transpot"  class="form-control form-control-select2" data-fouc>
									<option></option>
									@foreach ($getTraspot as $data)
										<option {{ selectAktif($siswa->profile_siswa->psTransport,$data->trsId) }}  value="{{ $data->trsId}}">{{ $data->trsNama}}</option>
									@endforeach
									</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">TANGGAL LAHIR</label>
								<input {{ $blokedit }} type="date" name="tgl" value="{{$siswa->profile_siswa->psTgl}}" placeholder="Tanggal" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">TEMPAT LAHIR</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTpl}}" required type="text" name="tpl" placeholder="Tempat Lahir" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">JARAK KE SEKOLAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psJarak}}" type="text" name="jarak" class="form-control" placeholder="Jarak Dari Rumah Ke Sekolah">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">ALAMAT</label>
								<textarea {{ $blokedit }} name="alamatsiswa" rows="1" cols="1" placeholder="Tempat Tinggal Siswa Sekarang" class="form-control">{{$siswa->profile_siswa->psAlamat}}</textarea>
							</div>
						</div>
					
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NO NIK </label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNik}}" type="text" name="nik" class="form-control" placeholder="No NIK">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NO KK </label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNoKK}}" type="text" name="kk" class="form-control" placeholder="No KK">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">TINGGI BADAN (satuan cm)</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTinggiBadan}}" type="text" name="tingibadan" class="form-control" placeholder="Contoh 170">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NO KKS</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNoKKS}}" type="text" name="nokks" class="form-control" placeholder="NO KKS">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NO PKH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNoPKH}}" type="text" name="nopkh" class="form-control" placeholder="No PKH">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">NO KIP</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNoKip}}" type="text" name="nokip" class="form-control" placeholder="NO KIP">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-1">
							<div class="form-group">
								<label class="cek">RT</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psRt}}" type="text" name="rt" class="form-control" placeholder="NO RT">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="cek">RW</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psRw}}" type="text" name="rw" class="form-control" placeholder="NO RW">
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">DESA</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psDesa}}" type="text" name="desa" class="form-control" placeholder="Nama Desa">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="cek">KECAMATAN</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psKecamatan}}" type="text" name="keca" class="form-control" placeholder="Kecamatan">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">KABUPATEN</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psKabupaten}}" type="text" name="kabut" class="form-control" placeholder="Kabupaten">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="cek">PROVINSI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psProvinsi}}" type="text" name="provinsi" class="form-control" placeholder="Provinsi">
							</div>
						</div>

					</div>

				</fieldset>
<!-- Data Ayah ----------------------------------------------->
				<h6>DATA AYAH</h6>
				<fieldset>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>NAMA AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNamaAyah}}" type="text" name="namaayah" placeholder="Nama Ayah" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NIK AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNikAyah}}" type="text" name="nikayah" placeholder="NIK AYAH" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>TEMPAT LAHIR AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTplAyah}}" type="text" name="tplayah" placeholder="Tempat Lahir Ayah" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>TANGGAL LAHIR AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTglAyah}}" type="date" name="tglayah" placeholder="Tanggal Lahir Ayah" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENDIDIKAN AKHIR AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPendidikanAyah}}" type="text" name="pdkayah" placeholder="Pendidikan Akhir Ayah" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>PEKERJAAN AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPekerjaanAyah}}" type="text" name="pkrayah" placeholder="Pekerjaan Ayah" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENGHASILAN AYAH</label>
								<select {{ $blokedit }} data-placeholder="Pilih Penghasilan"  name="phsayah" id="phsayah"  class="form-control form-control-select2" data-fouc>
									<option></option>
									@foreach ($getPenghasilan as $hasil)
										<option {{ selectAktif($siswa->profile_siswa->psPenghasilanAyah,$hasil->pnId) }} value="{{ $hasil->pnId}}">{{ $hasil->pnNama}}</option>
									@endforeach
									</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NO HP AYAH</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psHpAyah}}" type="text" name="hpayah" placeholder="No Hp Ayah" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>ALAMAT AYAH</label>
								<textarea {{ $blokedit }} name="alamatayah" rows="1" cols="1" placeholder="Alamat Tempat Tinggal Ayah " class="form-control">{{$siswa->profile_siswa->psAlamatAyah}}</textarea>
							</div>
						</div>
					</div>
				</fieldset>
<!-- Data Ibu ----------------------------------------------->
				<h6>DATA IBU</h6>
				<fieldset>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>NAMA IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNamaIbu}}" type="text" name="namaibu" placeholder="Nama Ibu" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>NIK IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNikIbu}}" type="text" name="nikibu" placeholder="NIK Ibu" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>TEMPAT LAHIR IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTplIbu}}" type="text" name="tplibu" placeholder="Tempapt Lahir Ibu" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>TANGGAL LAHIR IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTglIbu}}" type="text" name="tglibu" placeholder="Tanggal Lahir Ibu" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENDIDIKAN AKHIR IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPendidikanIbu}}" type="text" name="pdkibu" placeholder="Pendidikan Akhir Ibu" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>PEKERJAAN IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPekerjaanIbu}}" type="text" name="pkribu" placeholder="Pekerjaan Ibu" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENGHASILAN IBU</label>
								<select {{ $blokedit }} data-placeholder="Pilih Penghasilan"  name="phsibu" id="phsibu"  class="form-control form-control-select2" data-fouc>
									<option></option>
									@foreach ($getPenghasilan as $hasil)
										<option {{ selectAktif($siswa->profile_siswa->psPenghasilanIbu,$hasil->pnId) }} value="{{ $hasil->pnId}}">{{ $hasil->pnNama}}</option>
									@endforeach
									</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NO HP IBU</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psHpIbu}}" type="text" name="hpibu" placeholder="No Hp Ibu, jika tidak ada bisa di samakan dengan ayah" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>ALAMAT IBU</label>
								<textarea {{ $blokedit }} name="alamatibu" rows="1" cols="1" placeholder="Alamat Tempat Tinggal Ibu " class="form-control">{{$siswa->profile_siswa->psAlamatIbu}}</textarea>
							</div>
						</div>
					</div>
				</fieldset>
<!-- Data Wali ----------------------------------------------->
				<h6>DATA WALI</h6>
				<fieldset>
					<span>Wali yang merupkan pengganti ayah atau ibu <br>(seperti siswa-siswa yang kos )</span>
					<br><hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>NAMA WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNamaWali}}" type="text" name="namawali" placeholder="Nama Wali" class="form-control">
								
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NIK WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNikWali}}" type="text" name="nikwali" placeholder="Nik Wali" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>TEMPAT LAHIR WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTplWali}}" type="text" name="tplwali" placeholder="Tempat Lahir Wali" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>TANGGAL LAHIR WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psTglWali}}" type="text" name="tglwali" placeholder="Tangal Lahir Wali" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENDIDIKAN AKHIR WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPendidikanWali}}" type="text" name="pdkwali" placeholder="Pendidiakn Ahkir Wali" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>PEKERJAAN WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psPekerjaanWali}}" type="text" name="pkrwali" placeholder="Pekerjaan Wali" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>PENGHASILAN WALI</label>
								<select {{ $blokedit }} data-placeholder="Pilih Penghasilan"  name="phswali" id="phswali"  class="form-control form-control-select2" data-fouc>
									<option></option>
									@foreach ($getPenghasilan as $hasil)
										<option {{ selectAktif($siswa->profile_siswa->psPenghasilanWali,$hasil->pnId) }} value="{{ $hasil->pnId}}">{{ $hasil->pnNama}}</option>
									@endforeach
									</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NO HP WALI</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psHpWali}}" type="text" name="hpwali" placeholder="No Hp Wali" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>ALAMAT WALI</label>
								<textarea {{ $blokedit }} name="alamatwali" rows="1" cols="1" placeholder="Alamat Tempat Tinggal Wali " class="form-control">{{$siswa->profile_siswa->psAlamatWali}}</textarea>
							</div>
						</div>
					</div>
				</fieldset>
<!-- Data SMP ----------------------------------------------->
				<h6>DATA SMP</h6>
				<fieldset>
					<div class="row">
					<div class="col-md-6">
							<div class="form-group">
								<label>NAMA ASAL SMP</label>
								<select {{ $blokedit }} data-placeholder="Pilih SMP"  name="smp" id="smp"  class="form-control form-control-select3" data-fouc>
									<option></option>
									@foreach ($getSmp as $smp)
										<option {{ selectAktif($siswa->profile_siswa->psAsalSmp,$smp->smpId) }} value="{{ $smp->smpId}}">{{ $smp->smpNama}}</option>
									@endforeach
									</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NPSN SMP</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNpsnSmp}}" type="text" name="npsnsmp" placeholder="NPSN SMP" class="form-control">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>NOMOR UJIAN SMP</label>
								<input {{ $blokedit }} value="{{$siswa->profile_siswa->psNpsnSmp}}" type="text" name="noujiannsmp" placeholder="Nomor Ujian SMP" class="form-control">
							</div>
						</div>
						
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<br>
	<br>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
</div>
<!-- /content area -->
@endsection
@push('js_atas')
	<script src="{{ asset('global_assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/inputs/inputmask.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/extensions/cookie.js') }}"></script>
	{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endpush
@push('js_atas2')

@endpush
@push('jsku')
<script type="text/javascript">
var FormWizard = function() {

    var _componentWizard = function() {
        if (!$().steps) {
            console.warn('Warning - steps.min.js is not loaded.');
            return;
        }
        // Enable all steps and make them clickable
        $('.steps-enable-all').steps({
            headerTag: 'h6',
            bodyTag: 'fieldset',
            transitionEffect: 'fade',
            enableAllSteps: true,
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                previous: '<i class="icon-arrow-left13 mr-2" /> Previous',
                next: 'Next <i class="icon-arrow-right14 ml-2" />',
								finish: 'Simpan Data <i class="icon-arrow-right14 ml-2" />'
            },
            onFinished: function (event, currentIndex) {
                var route = $(this).data('route');
								var dataedit = $(this).data('edit');
                var data_form = $(this);
								if(dataedit==1){
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
											setInterval(function(){ location.reload(true); }, 1000);
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
								else{
									new Noty({
										theme: ' alert alert-danger alert-styled-left p-0',
										text: "Status Edit Profile di Nonaktifkan",
										type: 'error',
										progressBar: false,
										closeWith: ['button']
									}).show();
								}
            }
        });

      };
    var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-blue'
        });
    };

    // Select2 select
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        var $select = $('.form-control-select2').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
        var $select2 = $('.form-control-select3').select2();
        // Trigger value change when selection is made
        $select.on('change', function() {
            $(this).trigger('blur');
        });
        $select2.on('change', function() {
            $(this).trigger('blur');
        });
    };

    return {
        init: function() {
            _componentWizard();
            _componentUniform();
            _componentSelect2();
        }
    }
}();
document.addEventListener('DOMContentLoaded', function() {
    FormWizard.init();
});
</script>
@endpush

