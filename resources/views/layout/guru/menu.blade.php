<!--Sidebar mobile toggler -->
<style>
	.font-size-icon{
		font-size: 20px;
	}
</style>
<div class="sidebar-mobile-toggler text-center">
	<a href="#" class="sidebar-mobile-main-toggle">
		<i class="icon-arrow-left8"></i>
	</a>
	<span class="font-weight-semibold">MENU</span>
	<a href="#" class="sidebar-mobile-expand">
		<i class="icon-screen-full"></i>
		<i class="icon-screen-normal"></i>
	</a>
</div>
<!-- /sidebar mobile toggler -->

<!-- Sidebar content -->
<div class="sidebar-content">
		<!-- Main navigation -->
	<div class="card card-sidebar-mobile">
		<ul class="nav nav-sidebar" data-nav-type="accordion">

			<!-- Main -->
			{{-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu</div> <i class="icon-menu" title="Main"></i> --}}
			<li class="nav-item" style="padding-top: 30px;">
				<a href="{{ route('home.guru') }}" class="nav-link active">
					<i class="icon-home4"></i>
					<span>
						Beranda
					</span>
				</a>
			</li>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">
				Menu</div> <i class="icon-menu" title="Main"></i></li>
				<li class="nav-item">
					<a href="{{ route('guru.data.siswa') }}" class="nav-link">
						<i class="icon-folder-search text-info font-size-icon"></i> 
						<span>Data Siswa</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('guru.cari.data.siswa') }}" class="nav-link">
						<i class="icon-search4 text-primary font-size-icon"></i> 
						<span>Cari Siswa</span>
					</a>
				</li>
				<li class="nav-item nav-item-submenu ">
					<a href="#" class="nav-link"><i  class="footer-icon icon-reading text-success"></i> <span>Data Profile</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
						<li class="nav-item"><a href="{{ route('guru.data.akun') }}" class="nav-link "><i class="icon-user-check"></i>Akun</a></li>
						<li class="nav-item"><a href="{{ route('guru.data.profile') }}" class="nav-link "><i class="icon-profile"></i>Profile</a></li>
						{{-- <li class="nav-item"><a href="" class="nav-link "><i class="icon-plus22"></i>Upload Foto Profile</a></li> --}}
						
					</ul>
				</li>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">
				MENU ABSEN</div> <i class="icon-menu" title="Main"></i></li>
				<li class="nav-item nav-item-submenu ">
					<a href="#" class="nav-link"><i  class="font-size-icon mi-fingerprint text-warning "></i> <span>Absensi</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
						<li class="nav-item"><a href="{{ route('guru.jadwal.mapel.absen') }}" class="nav-link "><i class="mi-note-add font-size-icon"></i>Buat Jadwal Mapel </a></li>
						<li class="nav-item"><a href="{{ route('mapel.absen.manual.guru') }}" class="nav-link "><i class="icon-profile font-size-icon"></i>Absensi Mapel Manual</a></li>
						<li class="nav-item"><a href="{{ route('rincian.mapel.absen.guru') }}" class="nav-link "><i class="icon-calendar52 font-size-icon"></i>Rincian Absensi Mapel </a></li>
						<li class="nav-item"><a href="{{ route('total.mapel.absen.guru.bulan') }}" class="nav-link "><i class="icon-file-presentation font-size-icon"></i>Total Absensi Mapel </a></li>
						{{-- <li class="nav-item"><a href="{{ route('add.materi') }}" class="nav-link "><i class="icon-plus22"></i>Materi</a></li> --}}
						
					</ul>
				</li>
				<li class="nav-item nav-item-submenu ">
					<a href="#" class="nav-link"><i  class="icon-stats-growth font-size-icon text-purple-800 "></i> <span>Rekap Absensi</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
						<li class="nav-item"><a href="{{ route('rekap.mapel.absen.bulan.guru') }}" class="nav-link "><i class="icon-calendar2 font-size-icon"></i>Print Rincian Bulan</a></li>
					</ul>
				</li>
{{-- menu elerning --}}
			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">ELERNING</div> <i class="icon-menu" title="Main"></i></li>
			<li class="nav-item nav-item-submenu ">
				<a href="#" class="nav-link"><i  class="font-size-icon icon-books text-green-800 "></i> <span>Materi</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
					<li class="nav-item"><a href="{{ route('add.materi') }}" class="nav-link "><i class="icon-file-plus font-size-icon"></i>Tambah Materi</a></li>
					<li class="nav-item"><a href="{{ route('lihat.materi') }}" class="nav-link "><i class="icon-bookmark font-size-icon"></i>Materi</a></li>
					
				</ul>
			</li>


			<!-- /main -->
		</ul>
		
	</div>
	<!-- /main navigation -->
	
</div>


@push('js_bawah')
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endpush
@push('jsku')
{{-- <script type="text/javascript">	

</script> --}}
@endpush
<!-- /sidebar content-->
