@extends('master')
@section('titile', 'Tambah Siswa')
@section('content')
<!-- Content area -->
<div class="content">

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
			<div id="info"></div>
			<form id="addsiswa" method="post" data-route="{{ route('insertSiswa') }}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6">
						<fieldset>
							<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Biodata Siswa</legend>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Depan</label>
								<div class="col-lg-9">
									<input value="nama depan" required type="text" name="firstname" class="form-control" placeholder="Nama Siswa">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Belakang</label>
								<div class="col-lg-9">
									<input value="nama Belakang" required type="text" name="lastname" class="form-control" placeholder="Nama Siswa">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Usernam atau Id Finger Print</label>
								<div class="col-lg-9">
									<input value="1902" required type="text" name="ssaUsername" class="form-control" placeholder="Nama Siswa">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Password</label>
								<div class="col-lg-9">
									<input value="123" required type="text" name="password" class="form-control" placeholder="Nama Siswa">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Sekolah</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getSekolah as $skl)
										<option value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
									@endforeach
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jurusan Di Pilih</label>
								<div class="col-lg-9">
									<select required data-placeholder="Pilih Jurusan"  name="jrs" id="jrs"  class="form-control select-fixed-single" data-fouc>
									<option></option>
									@foreach ($getJurusan as $jrs)
										<option value="{{ $jrs->jrsId}}">{{ $jrs->jrsNama}}</option>
									@endforeach
									</select>
								</div>
							</div>

							

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Jenis Kelamin</label>
								<div class="col-lg-3">
									<select required name="jsk" class="form-control select-fixed-single" data-fouc data-placeholder="Jenis Kelamin">
										<optgroup label="Pilih">
											<option></option>
											<option value="L" selected="selected">Laki-Laki</option>
											<option value="P">Perempuan</option>
										</optgroup>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Tempat Tanggal Lahir</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input value="Way Jepara" required type="text" name="tpl" placeholder="Tempat Lahir" class="form-control">
										</div>
										<div class="col-md-6">
											<input type="date" name="tgl" placeholder="Tanggal" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">ASAL SMP</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-3">
											<select id="jenis_smp" name="jenis_smp" data-placeholder="SMP/MTS" class="form-control form-control-select2 jenis_smp" data-fouc>
												<option value="" >Pilih</option>
												<option value="1" >SMP</option>
												<option value="2" >MTS</option>
											</select>
										</div>
										<div class="col-md-9">
											<select id="asal_smp" name="asal_smp" data-placeholder="Pilih SMP/MTS Asal" class="form-control form-control-select2" data-fouc>
									</select>
										</div>
									</div>
								</div>
							</div>

						</fieldset>
					</div>

					<div class="col-md-6">
						<fieldset>
							
							<div class="form-group row">
								<label data-popup="tooltip" title="NIS SMK NISN Bisa Lihat di IJASA SD atau SKHU SMP" class="col-lg-3 col-form-label">NIS dan NISN</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input type="number" name="nis" placeholder="NIS" class="form-control" value="123">
										</div>
										<div class="col-md-6">
											<input type="number" name="nisn" placeholder="NISN" class="form-control" value="321">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nama Ayah dan Ibu</label>
								<div class="col-lg-9">
									<div class="row">
										<div class="col-md-6">
											<input type="text" name="ayah" placeholder="Nama Ayah" class="form-control" value="AYAH">
										</div>

										<div class="col-md-6">
											<input type="text" name="ibu" placeholder="Nama Ibu" class="form-control" value="IBU">
										</div>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">AGAMA</label>
								<div class="col-lg-9">
									<select  required name="agama"  class="form-control select-fixed-single" data-fouc data-placeholder="Pilih Agama">
											<option></option>
											@foreach ($getAgama as $agama)
												<option value="{{$agama->agmKode}}">{{$agama->agmNama}}</option>
											@endforeach
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label">No HP Siswa </label>
								<div class="col-lg-9">
									<input type="number" name="hpsiswa" placeholder="No Hp" class="form-control" value="082122758">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">No WA Siswa </label>
								<div class="col-lg-9">
									<input type="number" name="wasiswa" placeholder="No WA " class="form-control" value="082122758">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-lg-3 col-form-label" >No HP Ayah</label>
								<div class="col-lg-9">
									<input type="number" name="hpayah" placeholder="No HP Wali / Orang Tua" class="form-control" value="2215478">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label" >No HP Ibu</label>
								<div class="col-lg-9">
									<input type="number" name="hpibu" placeholder="No HP Wali / Orang Tua" class="form-control" value="2215478">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Alamat Siswa</label>
								<div class="col-lg-9">
									<textarea rows="2" name="alamatsiswa" cols="2" class="form-control" placeholder="Alamat">alamat</textarea>
								</div>
							</div>
							
						</fieldset>
					</div>
				</div>
				<div class="text-left">
					<font color="red" ><i>Bisa Mengguanan tombol TAB Pada Keyword untuk bergeser ke kolom selanjutnya</i></font>
				</div>
				<div class="text-right">
					<button type="submit" class="btn btn-primary ">Simpan Data <i class="icon-paperplane ml-2"></i></button>
					{{-- <button type="button" class="btn btn-light" id="notifikasi">Launch <i class="icon-play3 ml-2"></i></button> --}}
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
@endpush
@push('jsku')
<script type="text/javascript">
	$(document).ready(function(){
		$('#jenis_smp').change(function(){
			var id=$(this).val();
			var token = $("meta[name='csrf-token']").attr("content");
			//alert(id);
			$.ajax({
				url : "{{ route('pilih.smp') }}",
				method : "POST",
				data : {_token: token,id: id},
				async : false,
				dataType : 'json',
				success: function(data){
					//console.log(data);
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].smpId+'>'+data[i].smpNama+'</option>';
					}
					$('#asal_smp').html(html);
					
					

				}

			});
			return false;

		});
	}); 
	$('#addsiswa').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();

			//console.log(route);
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					console.log(respon);
					if(respon.ssaUsername){
						new Noty({
						theme: 'alert alert-danger alert-styled-left p-0',
						text: respon.ssaUsername,
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

