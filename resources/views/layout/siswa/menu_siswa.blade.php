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
				<a href="{{ route('homeSiswa')}}" class="nav-link active">
					<i class="icon-home4"></i>
					<span>
						Beranda
					</span>
				</a>
			</li>

			<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">
				Menu</div> <i class="icon-menu" title="Main"></i></li>
			<li class="nav-item">
				<a href="{{ route('data.sekolah') }}" class="nav-link">
					<i class="icon-office text-info font-size-icon"></i> 
					<span>PROFILE SEKOLAH</span>
				</a>
			</li>
			<li class="nav-item nav-item-submenu ">
				<a href="#" class="nav-link"><i  class="icon-reading font-size-icon text-success "></i> <span>AKUN BIDODATA </span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
					<li class="nav-item">
						<a href="{{ route('edit.akun.siswa')}}" class="nav-link">
							<i class="icon-person text-success font-size-icon" ></i> 
							<span>Akun Profile</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('foto.siswa') }}" class="nav-link">
							<i class="mi-assignment-ind text-teal font-size-icon" ></i> 
							<span>Foto Profile </span>
							{{-- icon-stack-picture --}}
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('password') }}" class="nav-link">
							<i class="icon-key font-size-icon"></i> 
							<span>Password</span>
						</a>
					</li>
					
				</ul>
			</li>
			<li class="nav-item nav-item-submenu ">
				<a href="#" class="nav-link"><i  class="icon-lan2 font-size-icon text-warning "></i> <span>KELAS</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
					<li class="nav-item">
						<a href="{{ route('anggota.kelas') }}" class="nav-link">
							<i class="icon-users4 text-warning font-size-icon"></i> 
							<span>Anggota Kelas</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('wali.kelas') }}" class="nav-link">
							<i class="mi-sentiment-satisfied text-primary-400 font-size-icon"></i> 
							<span>Wali Kelas</span>
						</a>
					</li>
					
				</ul>
			</li>
			<li class="nav-item nav-item-submenu ">
				<a href="#" class="nav-link"><i  class="mi-fingerprint font-size-icon text-primary "></i> <span>ABSENSI</span></a>
				<ul class="nav nav-group-sub" data-submenu-title="Layouts" >
					@if(Setting()->setSiswaAbsenSekolah ==1)
					<li class="nav-item">
						<a href="{{ route('absensi.sekolah') }}" class="nav-link">
							<i class="icon-hour-glass2 text-indigo-700 font-size-icon"></i> 
							<span>Absen Sekolah</span>
						</a>
					</li>
					@endif
					@if(Setting()->setSiswaAbsenSekolah ==1)
					<li class="nav-item">
						<a href="{{ route('total.absensi.sekolah') }}" class="nav-link">
							<i class="icon-basket text-indigo-700 font-size-icon"></i> 
							<span>Total Absen Sekolah</span>
						</a>
					</li>
					@endif
					
					@if(Setting()->setSiswaAbsenMapel ==1)
					<li class="nav-item">
						<a href="{{ route('absensi.mapel') }}" class="nav-link">
							<i class="icon-alarm-check text-info font-size-icon"></i> 
							<span>Absen Mata Pelajara</span>
						</a>
					</li>
					@endif
				
					
				</ul>
			</li>
			<li class="nav-item">
				<a href="{{ route('list.jadwal.mapel') }}" class="nav-link">
					<i class="icon-book text-info font-size-icon"></i> 
					<span>MATERI</span>
				</a>
			</li>
			

			{{-- <li class="nav-item">
				<a href="{{ route('jadwal.siswa') }}" class="nav-link">
					<i class="icon-clipboard2 text-primary"></i> 
					<span>Jadwal</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link">
					<i class="icon-alarm text-warning"></i> 
					<span>Absensi</span>
				</a>
			</li> --}}
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


