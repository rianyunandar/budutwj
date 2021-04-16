
<!-- Footer -->
<style type="text/css">
	.footer-icon{
		font-size: 20px;
	}
	
	</style>

<div class="navbar navbar-expand-lg navbar-light fixed-bottom ">
	<div class="text-center d-lg-none w-100 ">
		<div class="d-md-none">
			
			<div class="d-flex justify-content-center">{{-- agar menu tetap saat ukuran layar mengecil --}}
				<a href="{{ route('logout') }}" class=" btn btn-link btn-float font-size-sm font-weight-semibold text-default">
					<i class="footer-icon icon-switch2 text-danger"></i>
					<span>LOGOUT</span>
				</a>
				<a href="{{ route('guru.data.akun') }}" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default">
					<i  class="footer-icon icon-reading text-success"></i>
					<span>AKUN</span>
				</a>
				<a href="{{ route('home.guru') }}" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default">
					<i  class="footer-icon icon-home2 text-warning"></i>
					<span>BERANDA</span>
				</a>
				<a title="Menu"  class="sidebar-mobile-main-toggle btn btn-link btn-float font-size-sm font-weight-semibold text-default">
					<i class="footer-icon icon-paragraph-justify3 text-info"></i>
					<span>MENU</span>
				</a>
			</div>
		</div>
	</div>	
</div>
<!-- /footer -->