
@extends('master_guru')
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
			<div class="row pb-4">
				<div class="col-md-12">
					<a class="btn btn-dark" href="javascript:history.back()"><i class="icon-arrow-left52"></i> Kembali</a>
				</div>
			</div>
			<div class="card card-body">
				<div class="media align-items-center align-items-md-start flex-column flex-md-row">
					{{-- <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
						<i class="icon-question7 text-success-400 border-success-400 border-3 rounded-round p-2"></i>
					</a> --}}

					<div class="media-body text-center text-md-left">
						<center>
						<h6 class="media-title font-weight-semibold">{{ $getMateri->materiJudul }}</h6>
						{{ $getMateri->materiMapelNama }} | KI {{ $getMateri->materiKi }} | KD {{ $getMateri->materiKd }}
						| Pertemuan Ke {{ $getMateri->materiPertemuan }}
						</center>
					</div>

					
				</div>
			</div>
			<div class="row pb-3">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<label><b>Daftar Rombel yang dapat mengakses berdasarkan Jadwal Mapel yang di buat</b></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-xs">
									<thead>
										<tr class="bg-dark">
											<th>#</th>
											<th>ROMBEL</th>
											<th>TANGGAL TAMPIL</th>
											<th>HARI TAMPIL</th>
											<th>STATUS TAMPIL</th>
											<th>AKSI</th>
										</tr>
									</thead>
									<tbody>
										<?php $no=1; ?>
										@foreach ($getMateriRombel as $data)
										<tr>
											<td>{{ $no++ }}</td>
											<td><b>{{ $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama }}</b></td>
											<td>{{ date('d-m-Y H:i:s', strtotime($data->mtraTerbit))   }}</td>
											<td>{{ hariIndo(date('l', strtotime($data->mtraTerbit)))   }}</td>
											<td>
												@if($data->mtraTampilSiswa == 1)
													<span class="badge badge-primary">IYA</span>
												@else 
												<span class="badge badge-danger">TIDAK</span>
												@endif
											</td>
											<td>
												<div class="list-icons">
													<div class="dropdown">
														<a href="#" class="list-icons-item" data-toggle="dropdown">
															<i class="icon-menu9"></i>
														</a>
														<div class="dropdown-menu dropdown-menu-right">
															<button 
															data-tgl="{{ date('d-m-Y H:i:s', strtotime($data->mtraTerbit))   }}"
															data-id="{{ $data->mtraId }}"
															data-tampil="{{ $data->mtraTampilSiswa }}"
															class="dropdown-item edit" data-toggle="modal" data-target="#modal_iconified"><i class="icon-pencil7"></i> Edit</button>
															<a id="delete" data-id="{{ $data->mtraId }}"  class="dropdown-item"><i class="icon-trash"></i> Hapus</a>
														</div>
													</div>
												</div>

											</td>
										</tr>
											{{-- <div class="col-xl-3 col-md-6">
												<div class="card card-body " style="background-color: #05405a; color:white;">
													<div class="media">
														<div class="media-body">
															<div class="font-weight-semibold">
																<h5>{{ $data->master_rombel->master_kelas->klsKode.' '.$data->master_rombel->rblNama }}
																</h5>
															</div>
															<span >{{ $data->mtraTerbit }}</span>
														</div>
														<div class="ml-3 align-self-center">
														
															<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
																<li class="list-inline-item dropdown">
																	<a href="#" id="delete" data-id="{{ $data->mtraId }}"  class="text-white " >
																		<i class="icon-trash"> Hapus</i>
																	</a>
																
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div> --}}
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
				</div>
				<!-- Iconified modal -->
				<div id="modal_iconified" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><i class="icon-menu7 mr-2"></i> &nbsp; <b>Edit Rombel Akses Materi</b></h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 pb-2">
											<label>Tanggal Terbit</label>
											<input value="" id="eid" type="hidden" >
											<input value="" id="eterbit" type="text" placeholder="Masukan Tanggal" class="form-control timer">
										</div>

										<div class="col-sm-6">
											<label>Status Tampil</label>
											<select id="etampil" class="form-control select">
												<option value="1">Iya</option>
												<option value="0">Tidak</option>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<button class="btn btn-link" data-dismiss="modal"><i class="icon-cross2 font-size-base mr-1"></i> Close</button>
								<button id="editrblmateri" class="btn bg-primary "><i class="icon-checkmark3 font-size-base mr-1"></i> Save</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /iconified modal -->

				</div>
			</div>
			
			<div class="dropdown-divider"></div>
			<!-- Info alert -->
			<div class="alert alert-info alert-styled-left alert-arrow-left bg-white">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
				<h6 class="alert-heading font-weight-semibold mb-1">Informasi</h6>
				Menu di Bawah ini bisa di gunakan untuk menambah rombel yang bisa mengakses materi<br>
				Pilih kelas untuk menampilkan Rombel, Pilih rombel yang belum ada atau belum di beri akses
				</div>
				<!-- /info alert -->
			<div class="row">
				<div class="col-md-12">
				<form data-route="{{ route('update.materi.rombel') }}" id="insert" enctype="multipart/form-data" >
					@csrf
					<input type="hidden" value="{{ encrypt_url($getMateri->materiId) }}" name="idmateri">
					<input type="hidden" value="{{ encrypt_url($getMateri->materiKode) }}" name="kodemateri">
					<input type="hidden" value="{{ encrypt_url($getMateri->materiMapelKode) }}" name="kodemapel">
					<input type="hidden" value="{{ encrypt_url($getMateri->materiSklId) }}" name="idskl">

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label><b>KELAS</b></label><br>
								<select required data-placeholder="Pilih Kelas"  name="kelas" id="kelas"  class="form-control select">
									<option value=""></option>
									@foreach ($getKelas as $data)
										<option {{ selectAktif($getMateri->materiKlsId,$data->klsId) }} value="{{ encrypt_url($data['klsKode']) }}">{{ $data['klsKode'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label><b>SEKOLAH</b></label><br>
								<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select skl" >
								<option></option>
								<option {{ selectAktifEmpty($getMateri->materiSklId) }} value="{{ encrypt_url('ALL') }}">ALL</option>
								@foreach ($getSekolah as $skl)
									<option {{ selectAktif($getMateri->materiSklId,$skl->sklId) }} value="{{ encrypt_url($skl->sklId)}}">{{ $skl->sklNama}}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label><b>MAPEL</b></label><br>
									<select disabled  required data-placeholder="Pilih Mapel"  name="mapel" id="mapel"  class="form-control select-search" data-fouc>
									</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row pb-4">
								<div class="col-md-12">
									<label><b>ROMBEL</b></label><br>
											<select required data-placeholder="Pilih Rombel"  name="rbl[]" id="rbl"  class="form-control select-search" multiple data-fouc>
											</select>
											<hr>
								</div>
							</div>
						</div>
					</div>
					

						<div class="row">
							<div class="col-md-12">
							<div class="form-group">
								<button id="start"  type="submit" class="btn bg-blue ">Simpan  <i class="icon-paperplane ml-2"></i></button>
								<a id="proses"  style="display: none;" class="btn bg-dark ">Proses...  <i class="icon-spinner9 spinner ml-2"></i></a>
							</div>
						</div>
					</div>
				</form>
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
<!-- pluginnya -->

<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
{{-- <script src="{{ asset('global_assets/js/plugins/editors/summernote/summernote.min.js') }}"></script> --}}
<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
{{-- datetimepicker --}}
<script src="{{ asset('global_assets/js/plugins/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>

<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
@endpush
@push('js_bawah')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>

@endpush
@push('jsku')
<script type="text/javascript">
	$('.timer').datetimepicker({
		datepicker: true,
		format: 'd-m-Y H:i'
	});
	$('#skl').change(function(){
		var id=$(this).val();
		$('#kelas').val("");
		$('#rbl').val("");

		return false;
	});
	//apabila data sudah siap setelah di load atau di refres
	$( document ).ready(function() {
    	// var id=$(this).val();
			var skl=$('#skl').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				url : "{{ route('pilih.rombel.skl.kelas') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
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
				//url : "{{ route('pilih.mapel.sekolah.kelas') }}",
				url : "{{ route('pilih.jadwal.guru') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					console.log(data);
					var html = '';
					var mapel = "{{ $getMateri->materiMapelKode }}";
					var i;
					for(i=0; i<data.length; i++){
						if(mapel == data[i].majdMapelKode){
							var seleted = "selected";
						}else{ var seleted = ""; }
						//html += '<option '+seleted+' value='+data[i].kodemapel+'>'+data[i].namamapel+'</option>';
						html += '<option '+seleted+' value='+data[i].majdMapelKode+'>'+data[i].majdNama+'</option>';
					}
					$('#mapel').html(html);
				}
			});
			// return false;
	});
	$('#kelas').change(function(){
			// var id=$(this).val();
			var skl=$('#skl').val();
			var kelas=$('#kelas').val();
			
			$.ajax({
				url : "{{ route('pilih.rombel.skl.kelas') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
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
			// $.ajax({
			// 	url : "{{ route('pilih.mapel.sekolah.kelas') }}",
			// 	method : "POST",
			// 	data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
			// 	async : false,
			// 	dataType : 'json',
			// 	success: function(data){
			// 		console.log(data);
			// 		var html = '<option value="">Pilih Mapel</option>';
			// 		var i;
			// 		for(i=0; i<data.length; i++){
			// 			html += '<option value='+data[i].kodemapel+'>'+data[i].namamapel+'</option>';
			// 		}
			// 		$('#mapel').html(html);
			// 	}
			// });
			// return false;
		});
</script>
<script type="text/javascript">

	$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'POST',
				url:route,
				data:data_form.serialize(),
				beforeSend: function() {
					// $("#pesanku").text("Proses....");
					// $('.loader').show();
					$("#start").css("display", "none");
					$("#proses").css("display","");
      	},
				success:function(respon){
					console.log(respon);
					if(respon.success){
						//$('.loader').hide();
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					  // $("#start").css("display","");
						// $("#proses").css("display", "none");
					//tabel.ajax.reload();
					setInterval(function(){ location.reload(true); }, 1000);
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

<script type="text/javascript">
	$(document).on('click','.edit',function(e){
		var id = $(this).data("id");
		var tgl = $(this).data("tgl");
		var tampil = $(this).data("tampil");

		$('#eid').val(id);
		$('#eterbit').val(tgl);
		$('#etampil').val(tampil).change();

	});
	//editrblmateri
	$(document).on('click','#editrblmateri',function(e){
		var id = $("#eid").val();
		var tgl = $("#eterbit").val();
		var tampil = $("#etampil").val();
		var token = $("meta[name='csrf-token']").attr("content");
		$.ajax({
					type:'put',
					url:'{{ route('edit.data.materi.rombel') }}',
					data: { _token: token,id: id,tgl:tgl,tampil:tampil },
					beforeSend: function() {
						$("#pesanku").text("Proses....");
						$('.loader').show();
      		},
					success:function(respon){
						$('.loader').hide();
						//console.log(respon);
						if(respon.success){
							new Noty({
								theme: ' alert alert-success alert-styled-left p-0 bg-white',
								text: respon.success,
								type: 'success',
								progressBar: false,
								closeWith: ['button']
							}).show();
							setInterval(function(){ location.reload(true); }, 1000);
						}
					},
					error: function (respon) {
						console.log(respon);
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

<script type="text/javascript">
	$(document).on('click','#delete',function(e){
		var id = $(this).data("id");
		var token = $("meta[name='csrf-token']").attr("content");

		var notyConfirm = new Noty({
			text: '<center><h3 class="mb-3">Yakin Akan Menghapus Rombel Ini,</h3><center> ',
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
				//alert(id);
				$.ajax({
					type:'put',
					url:'{{ route('delete.materi.rombel') }}',
					data: { _token: token,id: id },
					beforeSend: function() {
						$("#pesanku").text("Proses....");
						$('.loader').show();
      		},
					success:function(respon){
						$('.loader').hide();
						console.log(respon);
						if(respon.success){
							new Noty({
								theme: ' alert alert-success alert-styled-left p-0 bg-white',
								text: respon.success,
								type: 'success',
								progressBar: false,
								closeWith: ['button']
							}).show();
							setInterval(function(){ location.reload(true); }, 1000);
						}
					},
					error: function (respon) {
						console.log(respon);
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
</script>

@endpush

