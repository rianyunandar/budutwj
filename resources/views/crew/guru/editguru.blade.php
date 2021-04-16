
@extends('master')
@section('titile', 'Tambah Jurusan')
@section('content')
<!-- Content area -->
<div class="content" id="content">

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
			<div id="info"></div>
			<div class="row">
					<div class="col-md-8"><i style="color: red">* Wajib Di Isi </i>  <i class="icon-info22 ml-1" data-popup="tooltip" title="Guru Juga Bisa Login Untuk Mengisi atau Melengkapi Data Profile" data-placement="bottom"></i></div>
				</div>
				<hr class="mt-1 mb-1"/>
			<form id="insert" data-route="{{ url('crew/update-guru/'.$idguru) }}">
				{{ csrf_field() }}
				<div class="row">
					{{-- kolom ke 1 --}}
					<div class="col-md-6">
						<fieldset>
							<div class="content-divider text-muted"><span class="px-2">Data Akun Pengguna</span></div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Hak Akses *</label>
								<div class="col-lg-9">
									<select multiple="multiple" required data-placeholder="Pilih Jabatan"  name="jabatan[]" id="jabatan[]"  class="form-control select" data-fouc>
									<option></option>
										@foreach ($getJabatan as $jbt)
											@if(empty($guru->ugrRole))
											<option value="{{ $jbt->mjbKode}}">{{ $jbt->mjbNama}}</option>
											@else
												@if(in_array($jbt->mjbKode, unserialize($guru->ugrRole)))
												<option selected value="{{ $jbt->mjbKode}}">{{ $jbt->mjbNama}}</option>
												@else
												<option value="{{ $jbt->mjbKode}}">{{ $jbt->mjbNama}}</option>
												@endif
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Sekolah *</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getSekolah as $skl)
										<option {{ selectAktif($guru->ugrSklId,$skl->sklId) }} value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jenis PTK</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih PTK"  name="ptk" id="ptk"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getPtk as $data)
										<option {{ selectAktif($guru->ugrPtkKode,$data->ptkKode) }} value="{{ $data->ptkKode}}">{{ $data->ptkNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Status Kepegawaian</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Status Pegawai"  name="spg" id="spg"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getPegawai as $data)
										<option {{ selectAktif($guru->ugrMskpKode,$data->mskpKode) }} value="{{ $data->mskpKode}}">{{ $data->mskpNama}}</option>
									@endforeach
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Username Guru *</label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrUsername}}" type="hidden" name="ugrUsername2" class="form-control" placeholder="Username Guru">
									<input value="{{ $guru->ugrUsername}}" required type="text" name="ugrUsername" class="form-control" placeholder="Username Guru">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Depan *</label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrFirstName}}" required type="text" name="firstname" class="form-control" placeholder="Nama Depan Guru">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Belakang *</label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrLastName}}" required type="text" name="lastname" class="form-control" placeholder="Nama Belakang Guru">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Lengkap *</label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrFullName}}" required type="text" name="fullname" class="form-control" placeholder="Nama Lengkap Guru">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">No HP </label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrHp}}" type="text" name="nohp" class="form-control" placeholder="Nomor Hp">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">No WA </label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrWa}}" type="text" name="nowa" class="form-control" placeholder="Nomor WA">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Email</label>
								<div class="col-lg-9">
									<input value="{{ $guru->ugrEmail}}" type="text" name="email" class="form-control" placeholder="Email Guru">
								</div>
							</div>
							<br><br>
							<div class="content-divider text-muted"><span class="px-2">Data Pendidikan Terakhir</span></div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">No Ijazah</label>
								<div class="col-lg-9">
									<input value="{{ $guru->profile_guru->prgNoIjazah}}" value="" type="text" name="ijazah" class="form-control" placeholder="No Ijazah Terakhir">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Kampus/Sekolah</label>
								<div class="col-lg-9">
									<input value="{{ $guru->profile_guru->prgNamaKampus}}" type="text" name="kampus" class="form-control" placeholder="Nama Kampus">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tanggal Lulus</label>
								<div class="col-lg-9">
									<input value="{{ $guru->profile_guru->prgTglLulus}}" type="date" name="tgl_lulus" placeholder="Tanggal" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">IPK</label>
								<div class="col-lg-9">
									<input value="{{ $guru->profile_guru->prgIpk}}" type="text" name="ipk" class="form-control" placeholder="IPK">
								</div>
							</div>


						</fieldset>
					</div>
					{{-- kolom ke 2 --}}
					<div class="col-md-6">
						<fieldset>
							<div class="content-divider text-muted"><span class="px-2">Data Pengguna</span></div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">AGAMA</label>
								<div class="col-lg-9">
									<select  required name="agama"  class="form-control select-fixed-single" data-fouc data-placeholder="Pilih Agama">
											<option></option>
											@foreach ($getAgama as $agama)
												<option {{selectAktif($guru->profile_guru->prgAgama,$agama->agmKode)}} value="{{$agama->agmKode}}">{{$agama->agmNama}}</option>
											@endforeach
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Pendidikan Terakhir</label>
								<div class="col-lg-9">
									<select  required name="pendidikan"  class="form-control select-fixed-single" data-fouc data-placeholder="Pilih Pendidikan">
											<option></option>
											@foreach ($getPendidikan as $data)
												<option {{selectAktif($guru->profile_guru->prgMpjKode,$data->mpjKode)}} value="{{$data->mpjKode}}">{{$data->mpjKode}}</option>
											@endforeach
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jenis Kelamin</label>
								<div class="col-lg-3">
									<select required name="jsk" class="form-control select-fixed-single" data-fouc data-placeholder="Pilih Jenis Kelamin">
										<optgroup label="Pilih">
											<option></option>
											<option {{selectAktif($guru->profile_guru->prgJsk,'L')}} value="L" >Laki-Laki</option>
											<option {{selectAktif($guru->profile_guru->prgJsk,'P')}} value="P">Perempuan</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tempat Tanggal Lahir</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgTpl}}" required type="text" name="tpl" placeholder="Tempat Lahir" class="form-control">
										</div>
										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgTgl}}" type="date" name="tgl" placeholder="Tanggal" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Ayah dan Ibu</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgNamaAyah}}" type="text" name="ayah" placeholder="Nama Ayah" class="form-control" >
										</div>

										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgNamaIbu}}" type="text" name="ibu" placeholder="Nama Ibu" class="form-control" >
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">RT/RW</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgRt}}" type="text" name="rt" placeholder="Rt" class="form-control" >
										</div>

										<div class="col-md-6">
											<input value="{{$guru->profile_guru->prgRw}}" type="text" name="rw" placeholder="Rw" class="form-control" >
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Desa</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgDesa}}" type="text" name="desa" class="form-control" placeholder="Nama Desa">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kabupaten</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgKabupaten}}" type="text" name="kabut" class="form-control" placeholder="Nama Kabupaten">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Kecamatan</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgKecamatan}}" type="text" name="keca" class="form-control" placeholder="Nama Kecamatan">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Provinsi</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgProvinsi}}" type="text" name="provinsi" class="form-control" placeholder="Nama Provinsi">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Alamat</label>
								<div class="col-lg-9">
									<textarea rows="2" name="alamat" cols="2" class="form-control" placeholder="Alamat">{{$guru->profile_guru->prgAlamat}}</textarea>
								</div>
							</div>
							<div class="content-divider text-muted"><span class="px-2">Tanggal Awal Masuk di Sekolah</span></div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tanggal Masuk di Sekolah</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgTglMasuk}}" type="text" name="tgl1" placeholder="Tanggal" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Bulan Masuk di Sekolah</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgBlnMasuk}}" type="text" name="bln1" placeholder="Bulan " class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tahun Masuk di Sekolah</label>
								<div class="col-lg-9">
									<input value="{{$guru->profile_guru->prgTahunMasuk}}" type="text" name="thn1" placeholder="Tahun" class="form-control">
								</div>
							</div>
						</fieldset>
					</div>

				</div>
				<div class="text-left">
					@if(AksiUpdate())
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					<a href="{{ route('lihat.guru') }}" class="btn bg-slate-600 btn-ladda btn-ladda-progress ladda-button legitRipple ">Kembali</a>
					@else
					<a class="btn bg-danger-600 btn-ladda btn-ladda-progress ladda-button legitRipple "><i class="icon-cross2"></i> NO AKSES</a>
					@endif
				</div>
			</form>
		</div>
	</div>
	<!-- /2 columns form -->
	<!-- /dashboard content -->
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<!-- pluginnya -->
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>

<script src="{{ asset('global_assets/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
@endpush
@push('jsku')
<script type="text/javascript">
	$('#insert').submit(function(e){
		var route = $(this).data('route');
		var data_form = $(this);
		e.preventDefault();
		$.ajax({
			type:'PUT',
			url:route,
			data:data_form.serialize(),
			success:function(respon){
				console.log(respon);
				if(respon.ugrUsername){
					new Noty({
						theme: 'alert alert-danger alert-styled-left p-0',
						text: respon.ugrUsername,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
				}
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

	});	
</script>
@endpush

