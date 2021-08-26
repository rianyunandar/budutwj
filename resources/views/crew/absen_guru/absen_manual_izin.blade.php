@extends('master')
@section('title')
@section('content')
<?php 
if(!empty($_GET['skl'])){ $getskl =$_GET['skl']; } else{ $getskl =''; }

// if(!empty($_GET['mapel'])){ $getmapel =$_GET['mapel']; } else{ $getmapel =''; }
?>
<div class="content">

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

		<form id="insert"  data-route="{{ route('insert.absen.manual.guru') }}">
			{{ csrf_field() }}
			
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-lg-4">
							<label>Sekolah</label>
							<select required data-placeholder="Pilih Sekolah"  name="skl" id="skl"  class="form-control select-search" data-fouc>
								<option></option>
								@foreach ($getSekolah as $skl)
								<option {{ selectAktif($getskl, encrypt_url($skl->sklId)) }} value="{{ encrypt_url($skl->sklId) }}">{{$skl->sklNama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-4">
							<label>Tanggal</label>
							
							<input type="text" class="form-control daterange-single" name="tgl" id="tgl" >
						</div>
						<div class="col-lg-2">
							<label>Jam Masuk</label>
							<input type="text" class="form-control" name="jamin" id="jamin"  value="{{ date("H:s") }}">
						</div>
						<div class="col-lg-2">
							<label>Jam Pulang</label>
							<input type="text" class="form-control" name="jamout" id="jamout" value="{{ date("H:s") }}">
						</div>
					</div>
				</div>
			</div>
			
			{{-- bagian data --}}
			@if(!empty($_GET['skl']))
			<div class="row">
				<div class="col-lg-2 mb-2">
					<button type="submit" class="btn btn-info">Klik Untuk Absen</button>
				</div>
				<div class="col-md-12">
					<h2>Silhakan Pilih Guru yang akan di absen</h2>
					<table id="tabel" class="table table-striped table-bordered  ">
						<thead style="background-color: #05405a; color: white;">
							<tr>
								<th> ALL<input type="checkbox" id='checkall' class="form-check-input-styled-success" ></th>
								<th>NAMA</th>
								{{-- <th>USERNAME</th> --}}
								<th>STATUS</th>
								<th>KETERANGAN</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=1; ?>
						
							@foreach ($dataguru as $data)
									<tr>
										<td><input value="{{ $data->ugrUsername }}" type="checkbox" id="{{ $no++ }}" name="cekpilih[]" class="cekpilih" ></td>
										<td>{{ $data->ugrFirstName.' '.$data->ugrLastName }}</td>
										{{-- <td>{{ $data->ugrUsername }}</td> --}}
										<td>
											<select required data-placeholder="Pilih Absen"  name="status[{{ $data->ugrUsername }}]" id="status[{{ $data->ugrUsername }}]"  class="form-control select-search" data-fouc>
												<option value="H">HADIR</option>
												{{-- <option value="B">BOLOS</option> --}}
												<option value="S">SAKIT</option>
												<option value="I">IZIN</option>
												{{-- <option value="T">TERLAMBAT</option> --}}
												<option value="K">KEGIATAN</option>
												<option value="U">UJIAN</option>
											</select>
										</td>
										<td>
											<textarea  class="form-control"  name="ktr[{{ $data->ugrUsername }}]" placeholder="Keterangan"></textarea>
										</td>
									</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			@endif
			</div>
		</form>

	</div>

</div>

@endsection
@push('js_atas')
<!-- pluginnya datatables-->
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<!-- Load Moment.js extension -->
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

{{-- Load Datetime --}}
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
{{-- datetimepicker --}}
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_checkboxes_radios.js') }}"></script>
@endpush
@push('js_atas2')
<!-- Load plugin -->
<script src="{{ asset('global_assets/js/selectku.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_layouts.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/components_popups.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
@endpush

@push('jsku')
<script type="text/javascript">
	$(document).ready(function() {
	
		$('.select-search').select2();
		$('#skl').change(function(){
			var skl = $('#skl').val();
			var rbl = $(this).val();
			location="?skl="+skl;
		});

		var tabel = $('#tabel').DataTable({
			language: {
				search: '<span>Cari:</span> _INPUT_',
				searchPlaceholder: 'Ketikan Di Sini',
				lengthMenu: '<span>Tampil:</span> _MENU_',
				paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
			},
			"lengthMenu": [ [10,50, 100, -1], [10,50, 100, "All"] ],
		
		});

	});
	//cek all
	$('#checkall').change(function () {
			$('.cekpilih').prop('checked',this.checked);
	});

	$('.ceksiswa').change(function () {
		if ($('.cekpilih:checked').length == $('.cekpilih').length){
			$('#checkall').prop('checked',true);
		}
		else {
			$('#checkall').prop('checked',false);
		}
	});
		// Single picker
		$('.daterange-single').daterangepicker({ 
      singleDatePicker: true,
// 			locale: {
// 				format: 'DD/MM/YYYY'
//       }
            
    });

		$('#insert').submit(function(e){
			var route = $(this).data('route');
			var data_form = $(this);
			e.preventDefault();
			$.ajax({
				type:'PUT',
				url:route,
				data:data_form.serialize(),
				beforeSend: function() {
					$("#pesanku").text("Proses ...!");
					$('.loader').show();
      	},
				success:function(respon){
					console.log(respon);
					if(respon.success){
						$('.loader').hide();
						new Noty({
						theme: ' alert alert-success alert-styled-left p-0 bg-white',
						text: respon.success,
						type: 'success',
						progressBar: false,
						closeWith: ['button']
					}).show();
					//setInterval(function(){ location.reload(true); }, 1000);
					}
					else{
						$('.loader').hide();
						new Noty({
						theme: ' alert alert-danger alert-styled-left p-0',
						text: respon.error,
						type: 'error',
						progressBar: false,
						closeWith: ['button']
						}).show();
					}
				},
				error: function (respon) {
					$('.loader').hide();
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
@endpush
