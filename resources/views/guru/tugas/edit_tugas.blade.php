
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
			<div class="row">
				<div class="col-md-12">
				<form data-route="{{ route('update.tugas.guru') }}" id="insert" enctype="multipart/form-data" >
					@csrf
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label><b>SEKOLAH</b></label><br>
								<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select skl" >
								<option></option>
								<option {{ selectAktifEmpty($getData->tugasSklId) }} value="{{ encrypt_url('ALL') }}">ALL</option>
									@foreach ($getSekolah as $skl)
										<option {{ selectAktif($getData->tugasSklId,$skl->sklId) }} value="{{ encrypt_url($skl->sklId)}}">{{ $skl->sklNama}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label><b>KELAS</b></label><br>
								<select required data-placeholder="Pilih Kelas"  name="kelas" id="kelas"  class="form-control select">
									<option value=""></option>
									@foreach ($getKelas as $data)
										<option {{ selectAktif($getData->tugasKlsId,$data->klsId) }} value="{{ encrypt_url($data['klsKode']) }}">{{ $data['klsKode'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label><b>MAPEL</b></label><br>
									<select  required data-placeholder="Pilih Mapel"  name="mapel" id="mapel"  class="form-control select-search" data-fouc>
									</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label><b> JUDUL TUGAS </b></label>
								<input value="{{ $id }}" required name="id" type="hidden" >
								<input value="{{ $getData->tugasJudul}}" required name="judul" type="text" class="form-control" placeholder="Judul Materi">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label><b> ISI TUGAS </b></label><br>
								<span class="text-silent">Silahkan Klik icon gambar atau file jika ingin menambah gambar atau file </span>
								<textarea id="summernote-editor" name="isi">
									{{ $getData->tugasIsi }}
								</textarea>
							</div>
						</div>
					</div>
					<div class="row pb-4">
						{{-- <div class="col-md-4 pb-2">
							<label><b>Tanggal Di Tampilkan</b></label><br>
							<input value="{{ $getMateri->materiTglMulai}}" type='text' id="tgl" name='tgl' class='timer form-control' autocomplete='off' required='true' />
						</div> --}}
						
						<div class="col-md-12 pb-2">
							<label><b>Link Url Tugas</b></label><br>
							<input value="{{ $getData->tugasLink}}" name="link_tugas" class="form-control" placeholder="Seperti Link Google Drive atau One Drive ataus Lainya" />
						</div>
						<div class="col-md-12 pb-2">
							<label><b>Link Url Untuk Download File</b></label><br>
							<input value="{{ $getData->tugasFile}}" name="link_download" class="form-control" placeholder="Seperti Link Google Drive atau One Drive atau Lainya" />
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="row pb-4">
								<div class="col-md-4 pb-2">
									<label><b>Status Tampil</b></label><br>
									<select required name="status" id="status"  class="form-control select">
										<option {{ selectAktif($getData->tugasTampil,1) }} value="1">AKTIF</option>
										<option {{ selectAktif($getData->tugasTampil,0) }} value="0">TIDAK AKTIF</option>
									</select>
								</div>
								<div class="col-md-3">
									<label><b>KI (Kopentensi Inti)</b></label><br>
									<input value="{{ $getData->tugasiKi}}" name="ki" class="form-control" placeholder="1" />
								</div>
								<div class="col-md-3">
									<label><b>KD (Kopentensi Dasar)</b></label><br>
									<input value="{{ $getData->tugasiKd}}" name="kd" class="form-control" placeholder="1.1" />
								</div>
								
							</div>
						</div>
					</div>

					<div class="row pb-4">
						<div class="col-md-12">
							<textarea name="pesan_singkat" class="form-control" placeholder="Pesan Singkat, Jika ada (100 karakter)" >{{ $getData->tugasPesanSingkat }}</textarea>
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

{{-- <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script> --}}
<link href="{{ asset('global_assets/css/summornote.css')}}" rel="stylesheet" type="text/css">
<script src="{{ asset('global_assets/js/summernote.js')}}"></script>
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
				// url : "{{ route('pilih.mapel.sekolah.kelas') }}",
				url : "{{ route('pilih.jadwal.guru') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					console.log(data);
					var html = '';
					var mapel = "{{ $getData->tugasMapelKode }}";
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
			$.ajax({
				//url : "{{ route('pilih.mapel.sekolah.kelas') }}",
				url : "{{ route('pilih.jadwal.guru') }}",
				method : "POST",
				data : {skl: skl,kelas:kelas,_token: "{{ csrf_token() }}"},
				async : false,
				dataType : 'json',
				success: function(data){
					console.log(data);
					var html = '<option value="">Pilih Mapel</option>';
					var i;
					for(i=0; i<data.length; i++){
						//html += '<option value='+data[i].kodemapel+'>'+data[i].namamapel+'</option>';
						html += '<option value='+data[i].majdMapelKode+'>'+data[i].majdNama+'</option>';
					}
					$('#mapel').html(html);
				}
			});
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
  $(document).ready(function(){

    // Define function to open filemanager window
    var lfm = function(options, cb) {
      var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
      window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
      window.SetUrl = cb;
    };
		
		var lfm2 = function(options, cb) {
      var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
      window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
      window.SetUrl = cb;
    };

    // Define LFM summernote button
    var LFMButton = function(context) {
      var ui = $.summernote.ui;
      var button = ui.button({
        contents: '<i class="icon-images2"></i> ',
        tooltip: 'Tambah Gambar',
        click: function() {

          lfm({type: 'image', prefix: '/laravel-filemanager'}, function(lfmItems, path) {
            lfmItems.forEach(function (lfmItem) {
              context.invoke('insertImage', lfmItem.url);
            });
          });

        }
      });
      return button.render();
    };

		var LFMButton2 = function(context) {
      var ui = $.summernote.ui;
      var button = ui.button({
        contents: '<i class="icon-file-plus"></i> ',
        tooltip: 'Tambah File',
        click: function() {

          lfm2({type: 'file', prefix: '/laravel-filemanager'}, function(lfmItems, path) {
            lfmItems.forEach(function (lfmItem) {
              context.invoke('insertText', lfmItem.url);
            });
          });

        }
      });
      return button.render();
    };

    // Initialize summernote with LFM button in the popover button group
    // Please note that you can add this button to any other button group you'd like
    $('#summernote-editor').summernote({
			codeviewFilter: false,
  		codeviewIframeFilter: true,
			placeholder: 'Ketik di sini untuk mengisi Materi',
			disableDragAndDrop: true,
      toolbar: [
        ['popovers', ['lfm','lfm2']],
				['style', ['bold', 'italic', 'underline', 'clear']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['height', ['height']],
				['insert', ['table']],
				['link', ['linkDialogShow', 'unlink']],
				// ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
				// ['float', ['floatLeft', 'floatRight', 'floatNone']],
				//['remove', ['removeMedia']],
				['view', ['fullscreen', 'codeview', 'help']],

      ],
			
      buttons: {
        lfm: LFMButton,
				lfm2: LFMButton2
      },
			

			
    })
  });
</script>

@endpush

