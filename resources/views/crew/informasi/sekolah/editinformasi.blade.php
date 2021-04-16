@extends('master')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	<!-- 2 columns form -->
	<div class="card">
		<div class="row pl-3 pt-2">
			<div class="col-md-3">
				<a href="{{ route('lihat.informasi.sekolah') }}" class="btn btn-dark" style="color: white"><i class="icon-circle-left2"></i> Kembali</a>
			</div>
		</div>
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
				<form data-route="{{ url('crew/update-informasi-sekolah/'.$idinfo) }}" id="update" enctype="multipart/form-data" >
					@csrf
					<div class="form-group">
						<label ><b>Sekolah</b></label>
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select " >
							<option></option>
							<option {{  selectAktif('ALL',$getInfo->infoIdTujuan) }} value="ALL">SEMUA</option>
							@foreach ($getSekolah as $skl)
								<option {{  selectAktif($skl->sklId,$getInfo->infoIdTujuan) }} value="{{ $skl->sklId}}">{{ $skl->sklNama}}</option>
							@endforeach
							</select>
					</div>
						<div class="form-group">
							<label><b> JUDUL INFORMASI </b></label>
							<input value="{{ $getInfo->infoJudul }}" name="judul" type="text" class="form-control" placeholder="Judul Infomasi">
						</div>
						<div class="form-group">
							<label><b> ISI INFORMASI </b></label>
							<textarea id="summernote-editor" name="isi">
								{{ $getInfo->infoIsi }}
							</textarea>
						</div>
						<div class="form-group">
							<button type="submit" class="btn bg-success ">Update  <i class="icon-paperplane ml-2"></i></button>
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

<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>


@endpush
@push('jsku')

<script type="text/javascript">

	$('#update').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'PUT',
				url:route,
				data:data_form.serialize(),
				success:function(respon){
					//console.log(respon);
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
<script>
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
        tooltip: 'Insert Gambar with filemanager',
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
        tooltip: 'Insert File with filemanager',
        click: function() {

          lfm2({type: 'file', prefix: '/laravel-filemanager'}, function(lfmItems, path) {
            lfmItems.forEach(function (lfmItem) {
              context.invoke('insertImage', lfmItem.url);
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
			placeholder: 'Ketik Di Sini',
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

