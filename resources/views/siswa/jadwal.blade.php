@extends('master_siswa')
@section('title')
@section('content')
<!-- Content area -->
<div class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Directory -->
			<div class="text-center mb-3 py-2">
				<h4 class="font-weight-semibold mb-1">Jadwal Pelajaran Kelas X</h4>
				<span class="text-muted d-block">SMK Budi Utomo 1 Way Jepara</span>
				<span class="text-muted d-block">Tahun Pelajara 2021/2022</span>
			</div>

			<div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title"><b>Jadwal Pelajaran</b></h6>
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
						{{-- <a class="list-icons-item" data-action="reload"></a>
						<a class="list-icons-item" data-action="remove"></a> --}}
					</div>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> Getting started <span class="ml-1">(93)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> And hello exotic staunch <span class="badge badge-primary ml-auto">Popular</span>
								</a>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> That and well ecstatically
								</a>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Sheared coasted so concurrent
								</a>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Into darn intrepid belated
								</a>
								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (93)
								</a>
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> Becoming an author <span class="ml-1">(56)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Jeepers therefore one
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Near and ladybug forewent <span class="badge badge-success ml-auto">Review</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Well much strove when stuck
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Lorikeet much fantastic less
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (56)
								</a>
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> General info for all authors <span class="ml-1">(29)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Lackadaisical dear crude <span class="badge badge-danger ml-auto">Closed</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Effortless one powerlessly
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Some less hey and less <span class="badge bg-indigo-300 ml-auto">Article</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Jeepers pill nonsensically
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (29)
								</a>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> Your statement &amp; documents <span class="ml-1">(58)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Incongruously gorilla <span class="badge bg-teal-300 ml-auto">New</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Playful amongst hence
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Sobbingly altruistic nasty
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Hung insecure far ferret
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (58)
								</a>
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> Account settings <span class="ml-1">(92)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Reined and this vigorous <span class="badge badge-primary ml-auto">Popular</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Oh positively well crab
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Recast then impalpable cried
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Eclectic mechanically as on <span class="badge badge-danger ml-auto">Closed</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (92)
								</a>
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<h6 class="font-weight-semibold mt-2"><i class="icon-folder6 mr-2"></i> Protecting your copyright <span class="ml-1">(15)</span></h6>
								<div class="dropdown-divider mb-2"></div>
								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> And dear dealt bat far redid
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Trout some after effective <span class="badge badge-secondary ml-auto">On hold</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> The one rhythmically whale
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-file-text2"></i> Admirably spun a the belched <span class="badge bg-indigo-300 ml-auto">Article</span>
								</a>

								<a href="#" class="dropdown-item">
									<i class="icon-arrow-right22"></i> Show all articles (15)
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /directory -->
		</div>
	</div>
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

{{-- Upload --}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>

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
	// {{-- Input upload --}}
	$('.file-input').fileinput({
		browseLabel: 'Cari',
		browseIcon: '<i class="icon-file-plus mr-2"></i>',
		//uploadIcon: '<i class="icon-file-upload2 "></i>',
		//removeIcon: '<i class="icon-cross2 font-size-base"></i>',
		initialCaption: "Tidak Ada File",
	});
	$('#upload').submit(function(e){
		var route = $(this).data('route');
		var data = new FormData(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data:data,
			processData: false,  // Important!
      contentType: false,
      cache: false,
      beforeSend: function() {
      	$("#pesanku").text("Proses Upload ...!");
      	$('.loader').show();
      },
			success:function(respon){
				//console.log(respon);
				if(respon.save){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.save,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					//setInterval(function(){ location.reload(true); }, 1000);
					document.getElementById("foto_upload").form.reset();
					$("#fotoprofile").load(location.href + " #fotoprofile");
					$("#fotoprofilemenu").load(location.href + " #fotoprofilemenu");
				}
				if(respon.error){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
				}
				if(respon.danger){
					$('.loader').hide();
					new Noty({
						theme: ' alert alert-warning alert-styled-left p-0',
						text: respon.danger,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
					}).show();
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}

		});
	});	
	$('#form').submit(function(e){
		var route = $(this).data('route');
		var data_form = $(this);
		e.preventDefault();
		$.ajax({
			type:'PUT',
			url:route,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
	});	

</script>
@endpush

