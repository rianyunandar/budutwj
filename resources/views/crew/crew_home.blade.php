@extends('master')
@section('title', 'ADMIN BUDUT')
@section('content')
<!-- Content area -->
<div class="content">

	<div class="row">
		<!-- page 1-->
		<div class="col-xl-8">
			<div class="row">
				{{-- menu 1 --}}
				<div class="col-lg-4">
					<div class="card bg-teal-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
										<?php $total=0; ?>
										@foreach (GetJumlahSiswaPerSekolah() as $data)
										<tr>
											<td>{{$data['sekolah']}}</td>
											<td>:</td>
											<td align="center">{{$data['jumlah']}} </td>
											<td> Siswa</td>
										</tr>
										<?php  $total += $data['jumlah'];  ?>
										@endforeach
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge bg-teal-800 badge-pill align-self-center ml-auto">{{$total}}</span>
							</div>

							<div>
								Jumlah Siswa 
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						
							<div id="server-load1"></div>
						
					</div>
					<!-- /members online -->
				</div>

				{{-- menu 2 --}}
				<div class="col-lg-4">
					<div class="card bg-warning-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
										<?php $total2=0; ?>
										@foreach (GetJumlahRombelPerSekolah() as $value)
											<tr>
												<td>{{$value['sekolah']}}</td>
												<td>:</td>
												<td align="center">{{$value['jumlah']}}</td>
												<td> Rombel</td>
											</tr>
											<?php $total2 += $value['jumlah']; ?>
										@endforeach
										<tr></tr>
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge badge-primary badge-pill align-self-center ml-auto">{{$total2}}</span>
							</div>

							<div>
								Jumlah Rombel
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						<div id="server-load"></div>
					</div>
					<!-- /current server load -->
				</div>
				{{-- menu 3 --}}
				<div class="col-lg-4">
					<div class="card bg-blue-400">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0">
									<table>
										<?php $totalguru=0; ?>
										@foreach (GetJumlahGuruPerSekolah() as $value)
										<tr>
											<td>{{$value['sekolah']}}</td>
											<td>:</td>
											<td align="center">{{$value['jumlah']}}</td>
											<td> Guru</td>
										</tr>
										<?php $totalguru += $value['jumlah']; ?>
										@endforeach
										<tr></tr>
									</table>
								</h3>
								<span style="font-size: 15px;" class="badge badge-success badge-pill align-self-center ml-auto">{{$totalguru}}</span>
							</div>

							<div>
								Jumlah Guru
								<div class="font-size-sm opacity-75"></div>
							</div>
						</div>

						<div id="server-load2"></div>
					</div>
					<!-- /today's revenue -->
				</div>
			</div>
		

			<!-- Support tickets -->
			<div class="card">
				<div class="card-header header-elements-sm-inline">
					<h6 class="card-title"><b>Rincian Data Siswa</b></h6>
					<div class="header-elements">
						<a class="text-default daterange-ranges font-weight-semibold cursor-pointer dropdown-toggle">
							<i class="icon-calendar3 mr-2"></i>
							<span>{{ hariIndo(date('l')) }}, {{ date('d') }}-{{ bulanIndo(date('m')) }}-{{ date('Y') }} </span>
						</a>
					</div>
				</div>
				<div class="card-body pb-0">
					<div class="card-body d-md-flex align-items-md-center justify-content-md-between flex-md-wrap">
						<div class="d-flex align-items-center mb-3 mb-md-0">
							<a href="{{ route('rincian.siswa')}}" class="btn btn-outline bg-teal-400 text-teal-400 border-teal-400 border-2"><i style="font-size: 25px;" class="mi-accessibility "></i> Total Siswa</a>
						</div>

						<div class="d-flex align-items-center mb-3 mb-md-0">
							<a href="{{ route('rincian.agama.siswa')}}" class="btn btn-outline bg-warning-400 text-warning-400 border-warning-400 border-2"><i style="font-size: 25px;" class="mi-all-inclusive "></i>  Total Agama Siswa</a>
						</div>

						<div class="d-flex align-items-center mb-3 mb-md-0">
							<a href="{{ route('rincian.transpot.siswa')}}" class="btn btn-outline bg-primary-400 text-primary-400 border-primary-400 border-2"><i style="font-size: 25px;" class="mi-directions-bike "></i>  Total Transpot Siswa</a>
							
						</div>
					</div>
					
				</div>
				<div id="messages-stats3"></div>
			</div>
			<!-- /support tickets -->


			<!-- Latest posts -->
			<div class="card border-1 border-info">
				<div class="card-header bg-info text-white header-elements-inline">
					<h6 class="card-title"><b>Jumlah Data Siswa Yang Belum Lengkap</b></h6>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
							<a class="list-icons-item" data-action="reload"></a>
							<a class="list-icons-item" data-action="remove"></a>
						</div>
					</div>
				</div>
        {{-- Data siswa per rombel dan kelas --}}
				<div class="card-body pb-0">
					<div class="table-responsive">
					<table class="table tab text-nowrap ">
						<thead>
							<tr>
								<th>NAMA</th>
								<th>ROMBEL</th>
								<th>JURUSAN</th>
								<th>AGAMA</th>
							</tr>
						</thead>
						<tbody>
							@foreach (GetCekDataSiswa() as $val)
								<tr>
								<td>
									<span ><span class="badge badge-mark border-blue mr-1"></span> {{$val['kode_sekolah']}}</span>
								</td>
								<td>
									<span >{{$val['jum_rombel']}} Siswa</span>
								</td>
								<td>
									<span >{{$val['jum_jurusan']}} Siswa</span>
								</td>
								<td>
									<span >{{$val['jum_agama']}} Siswa</span>
								</td>
								
							</tr>
							@endforeach
							
						</tbody>
					</table>
				</div>
				</div>
			</div>
			<!-- /latest posts -->
		</div>
		<!-- /page 1-->
	
		
		<!-- page 2-->
		<div class="col-xl-4">

			<!-- Sekolah-->
			<div class="card">
				<div class="table-responsive">
					<table class="table text-nowrap">
						<thead>
							<tr>
								<th>Kode Sekolah</th>
								<th>NPSN Sekolah</th>
								<th>Nama Sekolah</th>
							</tr>
						</thead>
						<tbody>
							@foreach (GetSekolah() as $val)
								<tr>
								<td>
									<span ><span class="badge badge-mark border-blue mr-1"></span> {{$val['kode']}}</span>
								</td>
								<td>
									<span >{{$val['npsn']}}</span>
								</td>
								<td>
									<span >{{$val['nama']}}</span>
								</td>
								
							</tr>
							@endforeach
							
						</tbody>
					</table>
				</div>
			</div>
			<!-- /Sekolah -->

			<!-- Progress counters -->
			<div class="row">
				<div class="col-sm-6">
					<!-- Perempuan-->
					<!-- Available hours -->
					<div class="card text-center">
						<div class="card-body">

							<!-- Progress counter -->
							<div class="svg-center position-relative" id="hours-available-progress1"></div>
							<!-- /progress counter -->
						</div>
						<div id="messages-stats1"></div>
					</div>
					<!-- /available hours -->
				</div>

				<div class="col-sm-6">
					<!-- Laki-Laki-->
					<!-- Productivity goal -->
					<div class="card text-center">
						<div class="card-body">
							<!-- Progress counter -->
							<div class="svg-center position-relative" id="goal-progress1"></div>
							<!-- /progress counter -->
						</div>
						<div id="messages-stats2"></div>
					</div>
					<!-- /productivity goal -->
				</div>
			</div>
			<!-- /progress counters -->


			<!-- Jumlah Siswa Per Jurusan -->
			<div class="card">
				<div class="card-header header-elements-inline bg-dark">
					<h5 class="card-title"><b>Siswa Per Jurusan </b></h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
							<a class="list-icons-item" data-action="reload"></a>
							<a class="list-icons-item" data-action="remove"></a>
						</div>
					</div>
				</div>

				<div class="card-body py-0" style="">
					<div class="row text-center">
						<div class="table-responsive">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th>Jurusan</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody id="per_jurusan">
									<?php $jumJrs=0; 
									$data=GetJumlahSiswaPerJurusan();
									?>
									@foreach ($data as $key => $value)
										<?php 
										if($value['idskl']==1) {
											$co ='<span class="badge badge-mark border-green mr-1"></span>'; }
											else{ $co ='<span class="badge badge-mark border-danger mr-1"></span>'; } 
											?>
										<tr>
											<td align="left">{!! $co !!} {{$value['jurusan'] }}</td>
											<td align="center"><b>{{$value['jumlah']}} Siswa</b></td>
										</tr>
										<?php $jumJrs += $value['jumlah']; ?>
									@endforeach
								</tbody>
								<tfoot>
									<th >Total</th>
									<th ><b>{{$jumJrs}}</b> Siswa</th>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<!-- Area chart -->
				<div id="messages-stats"></div>
				<!-- /area chart -->
				<!-- /sales stats -->
			</div>
			<!-- /Jumlah Siswa Per Jurusan -->

			<!-- My messages -->
			{{-- <div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title"><b> Siswa Per Kelas </b></h6>
					<div class="header-elements">
						<span><i class="icon-history text-warning mr-2"></i> Jul 7, 10:30</span>
						<span class="badge bg-success align-self-start ml-3">Online</span>
					</div>
				</div>

				<!-- Numbers -->
				<div class="card-body py-0">
					<div class="row text-center">
						<div class="col-4">
							<div class="mb-3">
								<h5 class="font-weight-semibold mb-0">KELAS X</h5>
								<span class="text-muted font-size-sm">this month</span>
							</div>
						</div>

						<div class="col-4">
							<div class="mb-3">
								<h5 class="font-weight-semibold mb-0">KELAS XI </h5>
								<span class="text-muted font-size-sm">this month</span>
							</div>
						</div>

						<div class="col-4">
							<div class="mb-3">
								<h5 class="font-weight-semibold mb-0">KELAS XII </h5>
								<span class="text-muted font-size-sm">this month</span>
							</div>
						</div>
					</div>
				</div>
				<!-- /numbers -->
				<!-- Area chart -->
				<div id="messages-stats"></div>
				<!-- /area chart -->
				<!-- Tabs -->
				<ul class="nav nav-tabs nav-tabs-solid nav-justified bg-indigo-400 border-x-0 border-bottom-0 border-top-indigo-300 mb-0">
					<li class="nav-item">
						<a href="#messages-tue" class="nav-link font-size-sm text-uppercase active" data-toggle="tab">
							ROMBEL X
						</a>
					</li>

					<li class="nav-item">
						<a href="#messages-mon" class="nav-link font-size-sm text-uppercase" data-toggle="tab">
							ROMBEL XI
						</a>
					</li>

					<li class="nav-item">
						<a href="#messages-fri" class="nav-link font-size-sm text-uppercase" data-toggle="tab">
							ROMBEL XII
						</a>
					</li>
				</ul>
				<!-- /tabs -->


				<!-- Tabs content -->
				<div class="tab-content card-body">
					<div class="tab-pane active fade show" id="messages-tue">
						<ul class="media-list">
							<li class="media">
								<div class="mr-3 position-relative">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
									<span class="badge bg-danger-400 badge-pill badge-float border-2 border-white">8</span>
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">James Alexander</a>
										<span class="font-size-sm text-muted">14:58</span>
									</div>

									The constitutionally inventoried precariously...
								</div>
							</li>

							<li class="media">
								<div class="mr-3 position-relative">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
									<span class="badge bg-danger-400 badge-pill badge-float border-2 border-white">6</span>
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Margo Baker</a>
										<span class="font-size-sm text-muted">12:16</span>
									</div>

									Pinched a well more moral chose goodness...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Jeremy Victorino</a>
										<span class="font-size-sm text-muted">09:48</span>
									</div>

									Pert thickly mischievous clung frowned well...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Beatrix Diaz</a>
										<span class="font-size-sm text-muted">05:54</span>
									</div>

									Nightingale taped hello bucolic fussily cardinal...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">												
									<div class="d-flex justify-content-between">
										<a href="#">Richard Vango</a>
										<span class="font-size-sm text-muted">01:43</span>
									</div>

									Amidst roadrunner distantly pompously where...
								</div>
							</li>
						</ul>
					</div>

					<div class="tab-pane fade" id="messages-mon">
						<ul class="media-list">
							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Isak Temes</a>
										<span class="font-size-sm text-muted">Tue, 19:58</span>
									</div>

									Reasonable palpably rankly expressly grimy...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Vittorio Cosgrove</a>
										<span class="font-size-sm text-muted">Tue, 16:35</span>
									</div>

									Arguably therefore more unexplainable fumed...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Hilary Talaugon</a>
										<span class="font-size-sm text-muted">Tue, 12:16</span>
									</div>

									Nicely unlike porpoise a kookaburra past more...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Bobbie Seber</a>
										<span class="font-size-sm text-muted">Tue, 09:20</span>
									</div>

									Before visual vigilantly fortuitous tortoise...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Walther Laws</a>
										<span class="font-size-sm text-muted">Tue, 03:29</span>
									</div>

									Far affecting more leered unerringly dishonest...
								</div>
							</li>
						</ul>
					</div>

					<div class="tab-pane fade" id="messages-fri">
						<ul class="media-list">
							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Owen Stretch</a>
										<span class="font-size-sm text-muted">Mon, 18:12</span>
									</div>

									Tardy rattlesnake seal raptly earthworm...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Jenilee Mcnair</a>
										<span class="font-size-sm text-muted">Mon, 14:03</span>
									</div>

									Since hello dear pushed amid darn trite...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Alaster Jain</a>
										<span class="font-size-sm text-muted">Mon, 13:59</span>
									</div>

									Dachshund cardinal dear next jeepers well...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Sigfrid Thisted</a>
										<span class="font-size-sm text-muted">Mon, 09:26</span>
									</div>

									Lighted wolf yikes less lemur crud grunted...
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
								</div>

								<div class="media-body">
									<div class="d-flex justify-content-between">
										<a href="#">Sherilyn Mckee</a>
										<span class="font-size-sm text-muted">Mon, 06:38</span>
									</div>

									Less unicorn a however careless husky...
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!-- /tabs content -->

			</div> --}}
			<!-- /my messages -->
		</div>
		<!-- /page 2-->
	
	</div>
	
</div>
<!-- /content area -->
@endsection
@push('js_atas')
<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
@endpush
@push('js_atas2')
<script src="{{ asset('global_assets/js/demo_pages/dashboard.js')}}"></script>
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

<script type="text/javascript">	
	// window.setTimeout("waktu()", 1000);
 
	// function waktu() {
	// 	var waktu = new Date();
	// 	setTimeout("waktu()", 1000);
	// 	document.getElementById("jam").innerHTML = waktu.getHours();
	// 	document.getElementById("menit").innerHTML = waktu.getMinutes();
	// 	document.getElementById("detik").innerHTML = waktu.getSeconds();
	//}
	
</script>


@endpush
@push('jsku')
<?php 
$jskl = GetAllJenisKelamin('L');
$jskp = GetAllJenisKelamin('P');

$jskl1 = "'#goal-progress1', 38, 2, '#5C6BC0',".$jskl['jml_presen'].", 'icon-man text-indigo-400', 'Total Pria', '".$jskl['jml_jsk']." Siswa'";
$jskp1 = "'#hours-available-progress1', 38, 2, '#F06292',".$jskp['jml_presen'].", 'icon-woman text-pink-400', 'Total Perempuan', '".$jskp['jml_jsk']." Siswa'";
?>
<script type="text/javascript">
	var DashboardProgress = function() {
	var _ProgressRoundedChart = function(element, radius, border, color, end, iconClass, textTitle, textAverage) {
			if (typeof d3 == 'undefined') {
					console.warn('Warning - d3.min.js is not loaded.');
					return;
			}

			// Initialize chart only if element exsists in the DOM
			if($(element).length > 0) {


					// Basic setup
					// ------------------------------

					// Main variables
					var d3Container = d3.select(element),
							startPercent = 0,
							iconSize = 32,
							endPercent = end,
							twoPi = Math.PI * 2,
							formatPercent = d3.format('.0%'),
							boxSize = radius * 2;

					// Values count
					var count = Math.abs((endPercent - startPercent) / 0.01);

					// Values step
					var step = endPercent < startPercent ? -0.01 : 0.01;



					// Create chart
					// ------------------------------

					// Add SVG element
					var container = d3Container.append('svg');

					// Add SVG group
					var svg = container
							.attr('width', boxSize)
							.attr('height', boxSize)
							.append('g')
									.attr('transform', 'translate(' + (boxSize / 2) + ',' + (boxSize / 2) + ')');



					// Construct chart layout
					// ------------------------------

					// Arc
					var arc = d3.svg.arc()
							.startAngle(0)
							.innerRadius(radius)
							.outerRadius(radius - border);



					//
					// Append chart elements
					//

					// Paths
					// ------------------------------

					// Background path
					svg.append('path')
							.attr('class', 'd3-progress-background')
							.attr('d', arc.endAngle(twoPi))
							.style('fill', color)
							.style('opacity', 0.2);

					// Foreground path
					var foreground = svg.append('path')
							.attr('class', 'd3-progress-foreground')
							.attr('filter', 'url(#blur)')
							.style('fill', color)
							.style('stroke', color);

					// Front path
					var front = svg.append('path')
							.attr('class', 'd3-progress-front')
							.style('fill', color)
							.style('fill-opacity', 1);



					// Text
					// ------------------------------

					// Percentage text value
					var numberText = d3.select(element)
							.append('h2')
									.attr('class', 'pt-1 mt-2 mb-1')

					// Icon
					d3.select(element)
							.append('i')
									.attr('class', iconClass + ' counter-icon')
									.attr('style', 'top: ' + ((boxSize - iconSize) / 2) + 'px');

					// Title
					d3.select(element)
							.append('div')
									.text(textTitle);

					// Subtitle
					d3.select(element)
							.append('div')
									.attr('class', 'font-size-sm text-muted mb-3')
									.text(textAverage);



					// Animation
					// ------------------------------

					// Animate path
					function updateProgress(progress) {
							foreground.attr('d', arc.endAngle(twoPi * progress));
							front.attr('d', arc.endAngle(twoPi * progress));
							numberText.text(formatPercent(progress));
					}

					// Animate text
					var progress = startPercent;
					(function loops() {
							updateProgress(progress);
							if (count > 0) {
									count--;
									progress += step;
									setTimeout(loops, 10);
							}
					})();
			}
	};


	//
	// Return objects assigned to module
	//

	return {
		
			init: function() {
					_ProgressRoundedChart(<?php echo $jskp1; ?>);
					_ProgressRoundedChart(<?php echo $jskl1;?>);
			}
	}
	}();


	// Initialize module
	// ------------------------------

	document.addEventListener('DOMContentLoaded', function() {
	DashboardProgress.init();
	});
</script>

@endpush

