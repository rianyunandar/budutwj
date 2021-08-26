<!--Sidebar mobile toggler -->
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
	
	<!-- User menu -->
	<div class="sidebar-user-material">
		<div class="sidebar-user-material-body">
			<div class="card-body text-center" id="fotoprofilemenu">
				<a href="{{ route('profile.admin') }}">
					<img width="80" height="80" src="<?php echo GetFotoMenu().'?date='.time(); ?>" class="img-fluid rounded-circle " alt="{{ FullNama() }}">
				</a>
				<h6 class="mb-0 text-white text-shadow-dark">{{ NamaDepan() }}</h6>
				<span class="font-size-sm text-white text-shadow-dark">
					
				</span>
			</div>

			<div class="sidebar-user-material-footer">
				<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse"><span>Data Akun Saya</span></a>
			</div>
		</div>

		<div class="collapse" id="user-nav">
			<ul class="nav nav-sidebar">
				<li class="nav-item">
					<a href="{{ route('profile.admin') }}" class="nav-link">
						<i class="icon-user-plus"></i>
						<span>Profile</span>
					</a>
				</li>
				@if(HakAksesFilterMenu() == true)
				<li class="nav-item">
					<a href="{{ route('list.akun.admin') }}" class="nav-link">
						<i class="icon-user-plus"></i>
						<span>Data Akun Admin</span>
					</a>
				</li>
				@endif
				
				{{-- <li class="nav-item">
					<a href="#" class="nav-link">
						<i class="icon-cog5"></i>
						<span>Akun Setting</span>
					</a>
				</li> --}}
				
			</ul>
		</div>
	</div>
	<!-- /user menu -->


	<!-- Main navigation -->
	<div class="card card-sidebar-mobile">
		<ul class="nav nav-sidebar" data-nav-type="accordion">

			<!-- Main -->
			{{-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu</div> <i class="icon-menu" title="Main"></i> --}}</li>
			<li class="nav-item">
				<a href="{{ url('crew/home')}}" class="nav-link active">
					<i class="icon-home4"></i>
					<span>
						Dashboard
					</span>
				</a>
			</li>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu MASTER DATA</div> <i class="icon-menu" title="Main"></i></li>
<!--Master Sekolah------------------------------------------------------------------------->
			<li class="nav-item nav-item-submenu {{ set_active_menu(['lihat.sekolah','lihat.jabatan','lihat.tahun.ajaran','lihat.tahun.ajaran','lihat.semester','lihat.agama','lihat.tingkat.pendidikan','lihat.transportasi','lihat.penghasilan','lihat.smp']) }}">
				<a href="#" class="nav-link"><i class="icon-office"></i> <span>Master Sekolah</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" 
				style="{{ set_active_submenu_blok(['lihat.sekolah','lihat.jabatan','lihat.tahun.ajaran','lihat.tahun.ajaran','lihat.semester','lihat.agama','lihat.tingkat.pendidikan','lihat.transportasi','lihat.penghasilan','lihat.smp']) }}">
					<li class="nav-item"><a href="{{ route('lihat.sekolah') }}" class="nav-link {{set_active_submenu('lihat.sekolah')}}"><i class="icon-plus22"></i>Lihat Sekolah</a></li>
					<li class="nav-item"><a href="{{ route('lihat.jabatan') }}" class="nav-link {{set_active_submenu('lihat.jabatan')}}"><i class="icon-user-tie"></i>Jabatan</a></li>
					<li class="nav-item"><a href="{{ route('lihat.tahun.ajaran') }}" class="nav-link {{set_active_submenu('lihat.tahun.ajaran')}}"><i class="icon-flag7"></i>Tahun Ajaran</a></li>
					<li class="nav-item"><a href="{{ route('lihat.semester') }}" class="nav-link {{set_active_submenu('lihat.semester')}}"><i class="icon-flag8"></i>Semester</a></li>
					<li class="nav-item"><a href="{{ route('lihat.agama') }}" class="nav-link {{set_active_submenu('lihat.agama')}}"><i class="icon-balance"></i>Agama</a></li>
					<li class="nav-item"><a href="{{ route('lihat.smp') }}" class="nav-link {{set_active_submenu('lihat.smp')}}"><i class="icon-cash3"></i>SMP</a></li>
					<li class="nav-item"><a href="{{ route('lihat.tingkat.pendidikan') }}" class="nav-link {{set_active_submenu('lihat.tingkat.pendidikan')}}"><i class="icon-earth"></i>Tingkat Pendidikan</a></li>
					<li class="nav-item"><a href="{{ route('lihat.transportasi') }}" class="nav-link {{set_active_submenu('lihat.transportasi')}}"><i class="icon-bike"></i>Transportasi</a></li>
					<li class="nav-item"><a href="{{ route('lihat.penghasilan') }}" class="nav-link {{set_active_submenu('lihat.penghasilan')}}"><i class="icon-cash3"></i>Penghasilan</a></li>
					
				</ul>
			</li>
<!--Master Akun Siswa------------------------------------------------------------------------->
			<li class="nav-item nav-item-submenu {{set_active_menu(['form.import.siswa','lihatsiswa','lihatsemuadatasiswa','tambah.siswa','lihat.siswa.off'])}}">
				<a href="#" class="nav-link"><i class="icon-people"></i> <span>Master Akun Siswa</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{ set_active_submenu_blok(['form.import.siswa','lihatsiswa','lihatsemuadatasiswa','tambah.siswa','lihat.siswa.off']) }}">
					@if(HakAkses())
					<li class="nav-item"><a href="{{ route('form.import.siswa') }}" class="nav-link {{set_active_submenu('form.import.siswa')}}"><i class="icon-upload"></i> Import / Update Siswa</a></li>
					<li class="nav-item"><a href="{{ route('tambah.siswa') }}" class="nav-link {{set_active_submenu('tambah.siswa')}}"><i class="icon-plus22"></i>Tambah Siswa</a></li>
					@endif
					<li class="nav-item"><a href="{{ route('lihatsiswa') }}" class="nav-link {{set_active_submenu('lihatsiswa')}}"><i class="icon-users2"></i> Akun Siswa</a></li>
					<li class="nav-item"><a href="{{ route('lihatsemuadatasiswa') }}" class="nav-link {{set_active_submenu('lihatsemuadatasiswa')}}"><i class="icon-users4"></i> Semua Data Siswa</a></li>
					<li class="nav-item"><a href="{{ route('lihat.siswa.off') }}" class="nav-link {{set_active_submenu('lihat.siswa.off')}}"><i class="icon-user-block text-danger "></i> Siswa OFF</a></li>
				</ul>
			</li>

<!--Master Jurusan------------------------------------------------------------------------->
			<li class="nav-item nav-item-submenu {{set_active_menu(['lihatJurusan'])}}">
				<a href="#" class="nav-link"><i class="icon-server"></i> <span>Master Jurusan</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{ set_active_submenu_blok(['lihatJurusan'])}}">
					<li class="nav-item"><a href="{{ route('lihatJurusan') }}" class="nav-link {{set_active_submenu('lihatJurusan')}}"><i class="icon-tree7"></i>Lihat Jurusan</a></li>
					
				</ul>
			</li>
<!--Master Rombel------------------------------------------------------------------------->		
			<li class="nav-item nav-item-submenu {{set_active_menu(['tambah.rombel','lihat.rombel','tambah.anggota.rombel','lihat.anggota.rombel'])}}">
				<a href="#" class="nav-link"><i class="icon-users4"></i> <span>Master Rombel</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['tambah.rombel','lihat.rombel','tambah.anggota.rombel','lihat.anggota.rombel'])}}">
					{{-- <li class="nav-item"><a href="{{ url('crew/addsiswa')}}" class="nav-link active"><i class="icon-plus22"></i>Tambah Kelas</a></li> --}}
					@if(HakAkses())
					<li class="nav-item"><a href="{{ route('tambah.rombel') }}" class="nav-link {{set_active_submenu('tambah.rombel')}}"><i class="icon-plus22"></i>Tambah Rombel</a></li>
					@endif
					<li class="nav-item"><a href="{{ route('lihat.rombel') }}" class="nav-link {{set_active_submenu('lihat.rombel')}}"><i class="icon-folder"></i>Data Rombel</a></li>
					@if(HakAkses())
					<li class="nav-item"><a href="{{ route('tambah.anggota.rombel') }}" class="nav-link {{set_active_submenu('tambah.anggota.rombel')}}"><i class="icon-plus22"></i>Tambah Anggota Rombel</a></li>
					<li class="nav-item"><a href="{{ route('lihat.anggota.rombel') }}" class="nav-link {{set_active_submenu('lihat.anggota.rombel')}}"><i class="icon-folder"></i>Data Anggota Romboel</a></li>
					@endif
				</ul>
			</li>
<!--Master Guru------------------------------------------------------------------------->			
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
				<a href="#" class="nav-link"><i class="icon-graduation2"></i> <span>Master Guru</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
					{{-- guru --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru'])}}">
						<a href="#" class="nav-link legitRipple">Data Guru</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['add.guru','lihat.guru'])}}">
							@if(HakAkses())
							<li class="nav-item"><a href="{{ route('form.import.guru') }}" class="nav-link {{set_active_submenu('form.import.guru')}}"><i class="icon-upload"></i> Import Data Guru</a></li>
							<li class="nav-item"><a href="{{ route('add.guru') }}" class="nav-link {{set_active_submenu('add.guru')}}"><i class="icon-plus22"></i>Tambah Guru</a></li>
							@endif
							<li class="nav-item"><a href="{{ route('lihat.guru') }}" class="nav-link {{set_active_submenu('lihat.guru')}}"><i class="icon-users2"></i>Lihat Guru</a></li>
							<li class="nav-item"><a href="{{ route('lihat.guru.off') }}" class="nav-link {{set_active_submenu('lihat.guru.off')}}"><i class="icon-user-block text-danger "></i>Lihat Guru OFF</a></li>
						</ul>
					</li>
					{{-- wali kelas --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['tambah.wali.kelas','lihat.wali.kelas'])}}">
						<a href="#" class="nav-link legitRipple">Data Wali Kelas</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['tambah.wali.kelas','lihat.wali.kelas'])}}">
							@if(HakAkses())
							<li class="nav-item"><a href="{{ route('tambah.wali.kelas') }}" class="nav-link {{set_active_submenu('tambah.wali.kelas')}}"><i class="icon-plus22"></i>Tambah Wali Kelas</a></li>
							@endif
							<li class="nav-item"><a href="{{ route('lihat.wali.kelas') }}" class="nav-link {{set_active_submenu('lihat.wali.kelas')}} legitRipple"><i class="icon-users2"></i>Lihat Wali Kelas</a></li>
						</ul>
					</li>
					{{-- ketua jurusan --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['tambah.kajur','lihat.kajur'])}}">
						<a href="#" class="nav-link legitRipple">Data Ketua Jurusan</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['tambah.kajur','lihat.kajur'])}}">
							@if(HakAkses())
							<li class="nav-item"><a href="{{ route('tambah.kajur') }}" class="nav-link {{set_active_submenu('tambah.kajur')}} legitRipple"><i class="icon-plus22"></i>Tambah Ketua Jurusan</a></li>
							@endif
							<li class="nav-item"><a href="{{ route('lihat.kajur') }}" class="nav-link {{set_active_submenu('lihat.kajur')}} legitRipple"><i class="icon-users2"></i>Lihat Ketua Jurusan</a></li>
						</ul>
					</li>
				</ul>
			</li>
<!--Master Mata Pelajaran-------------------------------------------------------------------->
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
				<a href="#" class="nav-link"><i class="icon-book3"></i> <span>Master Mapel</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.guru','lihat.guru','tambah.wali.kelas','lihat.wali.kelas','tambah.kajur','lihat.kajur'])}}">
					{{-- guru --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru'])}}">
						<a href="#" class="nav-link legitRipple">Data Mapel</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['add.guru','lihat.guru'])}}">
							@if(HakAkses())
							<li class="nav-item"><a href="{{ route('form.import.mapel') }}" class="nav-link {{set_active_submenu('form.import.mapel')}}"><i class="icon-upload"></i> Import Data Mapel</a></li>
							<li class="nav-item"><a href="{{ route('add.mapel') }}" class="nav-link {{set_active_submenu('add.mapel')}}"><i class="icon-plus22"></i>Tambah Mapel</a></li>
							@endif
							<li class="nav-item"><a href="{{ route('lihat.mapel') }}" class="nav-link {{set_active_submenu('lihat.guru')}}"><i class="icon-users2"></i>Lihat Mapel</a></li>
						</ul>
					</li>
					
				</ul>
			</li>
<!--Master Informasi------------------------------------------------------------------------->		
			@if(HakAkses())
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.informasi.sekolah','lihat.informasi.sekolah'])}}">
				<a href="#" class="nav-link"><i class="icon-volume-high"></i> <span>Master Informasi</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.informasi.sekolah','lihat.informasi.sekolah'])}}">
					{{-- sekolah --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['add.informasi.sekolah','lihat.informasi.sekolah'])}}">
						<a href="#" class="nav-link legitRipple">Informasi Sekolah</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['add.informasi.sekolah','lihat.informasi.sekolah'])}}">
							<li class="nav-item"><a href="{{ route('add.informasi.sekolah') }}" class="nav-link {{set_active_submenu('add.informasi.sekolah')}}"><i class="icon-plus22"></i>Tambah Info</a></li>
							<li class="nav-item"><a href="{{ route('lihat.informasi.sekolah') }}" class="nav-link {{set_active_submenu('lihat.informasi.sekolah')}}"><i class="icon-satellite-dish2"></i>Lihat Info</a></li>
						</ul>
					</li>
					{{-- guru --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['add.guru','lihat.guru'])}}">
						<a href="#" class="nav-link legitRipple">Informasi Guru</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['add.guru','lihat.guru'])}}">
							<li class="nav-item"><a href="{{ route('add.guru') }}" class="nav-link {{set_active_submenu('add.guru')}}"><i class="icon-plus22"></i>Tambah Guru</a></li>
							<li class="nav-item"><a href="{{ route('lihat.guru') }}" class="nav-link {{set_active_submenu('lihat.guru')}}"><i class="icon-users2"></i>Lihat Guru</a></li>
						</ul>
					</li>
					{{-- wali kelas --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['tambah.wali.kelas','lihat.wali.kelas'])}}">
						<a href="#" class="nav-link legitRipple">Informasi Siswa</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['tambah.wali.kelas','lihat.wali.kelas'])}}">
							<li class="nav-item"><a href="{{ route('tambah.wali.kelas') }}" class="nav-link {{set_active_submenu('tambah.wali.kelas')}}"><i class="icon-plus22"></i>Tambah Wali Kelas</a></li>
							<li class="nav-item"><a href="{{ route('lihat.wali.kelas') }}" class="nav-link {{set_active_submenu('lihat.wali.kelas')}} legitRipple"><i class="icon-users2"></i>Lihat Wali Kelas</a></li>
						</ul>
					</li>
					{{-- ketua jurusan --}}
					<li class="nav-item nav-item-submenu {{set_active_menu(['tambah.kajur','lihat.kajur'])}}">
						<a href="#" class="nav-link legitRipple">Informasi Ortu</a>
						<ul class="nav nav-group-sub" style="{{set_active_submenu_blok(['tambah.kajur','lihat.kajur'])}}">
							<li class="nav-item"><a href="{{ route('tambah.kajur') }}" class="nav-link {{set_active_submenu('tambah.kajur')}} legitRipple"><i class="icon-plus22"></i>Tambah Ketua Jurusan</a></li>
							<li class="nav-item"><a href="{{ route('lihat.kajur') }}" class="nav-link {{set_active_submenu('lihat.kajur')}} legitRipple"><i class="icon-users2"></i>Lihat Ketua Jurusan</a></li>
						</ul>
					</li>
				</ul>
			</li>
			@endif
<!--Master Akun Siswa Alumni------------------------------------------------------------------------->
		<li class="nav-item nav-item-submenu {{set_active_menu(['add.alumni','all.alumni','hapus.siswa.angkatan'])}}">
			<a href="#" class="nav-link"><i class="icon-footprint"></i> <span>Master Alumni</span></a>
			<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{ set_active_submenu_blok(['add.alumni','all.alumni','hapus.siswa.angkatan']) }}">
				<li class="nav-item"><a href="{{ route('add.alumni') }}" class="nav-link {{set_active_submenu('add.alumni')}}"><i class="icon-users2"></i> Tambah Alumni</a></li>
				<li class="nav-item"><a href="{{ route('all.alumni') }}" class="nav-link {{set_active_submenu('all.alumni')}}"><i class="icon-archive"></i> Data Alumni</a></li>
				<li class="nav-item"><a href="{{ route('hapus.siswa.angkatan') }}" class="nav-link {{set_active_submenu('hapus.siswa.angkatan')}}"><i class="icon-trash"></i> Hapus Data Siswa</a></li>
				{{-- <li class="nav-item"><a href="{{ route('lihat.siswa.off') }}" class="nav-link {{set_active_submenu('lihat.siswa.off')}}"><i class="icon-user-block text-danger "></i> Alumni OFF</a></li> --}}
			</ul>
		</li>
			@if(HakAkses())
			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu Absensi Siswa</div> <i class="icon-menu" title="Main"></i></li>
<!--Upload Absen Finger------------------------------------------------------------------------->	
			<li class="nav-item"><a href="{{ route('form.import.absen.finger') }}" class="nav-link {{set_active_submenu('form.import.absen.finger')}} legitRipple"><i class="icon-upload"></i> <span>Upload Absen Finger</span></a></li>
<!--Absen Finger------------------------------------------------------------------------->	
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.absen.finger','lihat.absen.finger'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="mi-alarm"></i> <span>Absen Finger</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.absen.finger','lihat.absen.finger'])}}">
					<li class="nav-item"><a href="{{ route('add.absen.finger') }}" class="nav-link {{set_active_submenu('add.absen.finger')}}"><i class="icon-plus22"></i>Tambah Absen</a></li>
					<li class="nav-item"><a href="{{ route('lihat.absen.finger') }}" class="nav-link {{set_active_submenu('lihat.absen.finger')}}"><i class="icon-clipboard6"></i>Lihat Absen</a></li>

				</ul>
			</li>
<!--Rekap Absen Finger------------------------------------------------------------------------->	
			<li class="nav-item nav-item-submenu {{set_active_menu(['asdw'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="mi-fingerprint"></i> <span>Rekap Absen Finger</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['view.rekap.absen.finger'])}}">
					<li class="nav-item"><a href="{{ route('view.rekap.absen.finger') }}" class="nav-link {{set_active_submenu('view.rekap.absen.finger')}}"><i class="icon-clipboard5"></i>Rekap Absen Bulan </a></li>

				</ul>
			</li>
			@endif
			@if(HakAkses() or Auth::user()->admRole == "KEPSEK")
			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu Absensi Guru</div> <i class="icon-menu" title="Main"></i></li>
			<li class="nav-item nav-item-submenu {{set_active_menu(['add.absen.finger','lihat.absen.finger'])}}">
				<a href="#" class="nav-link"><i style="font-size: 23px" class="mi-alarm"></i> <span>Absen Guru</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" style="{{set_active_submenu_blok(['add.absen.finger','lihat.absen.finger'])}}">
					{{-- <li class="nav-item"><a href="{{ route('add.absen.finger') }}" class="nav-link {{set_active_submenu('add.absen.finger')}}"><i class="icon-plus22"></i>Tambah Absen</a></li> --}}
					<li class="nav-item"><a href="{{ route('manual.absen.guru') }}" class="nav-link {{set_active_submenu('manual.absen.guru')}}"><i class="icon-clipboard6"></i>Absen Manual</a></li>
					<li class="nav-item"><a href="{{ route('lihat.absen.guru') }}" class="nav-link {{set_active_submenu('lihat.absen.guru')}}"><i class="icon-clipboard6"></i>Lihat Absen</a></li>
					<li class="nav-item"><a href="{{ route('rekap.absen.guru') }}" class="nav-link {{set_active_submenu('rekap.absen.guru')}}"><i class="icon-clipboard6"></i>Rekap Absen</a></li>

				</ul>
			</li>
			@endif
			<!-- /main -->
		</ul>
	</div>
	<!-- /main navigation -->
</div>
@push('js_atas2')
{{-- notifikasi --}}
<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endpush
@push('jsku')
{{-- <script type="text/javascript">	

</script> --}}
@endpush
<!-- /sidebar content-->
