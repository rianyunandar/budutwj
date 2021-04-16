
<div class="navbar navbar-expand-md navbar-dark fixed-top color_mryes">
		<div class="navbar-brand">
			<a href="" class="d-inline-block">
				<img src="../../../../global_assets/images/logo_light.png" alt="">
			</a>
		</div>
		<div class="d-md-none">
			<div class="navbar-toggler" id="watch"></div>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" style="font-size: 10px;">
				{{ FullNamaSiswa()}}
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			
			</ul>
			<span class="navbar-text ml-md-3">
				<span class="badge badge-mark border-orange-300 mr-2"></span>
				{{ env('AKADEMIK_WELCOME') }}
			</span>
			
			<span class="badge bg-success ml-md-3 mr-md-auto">Online</span>
			<div id="watch2"></div>
			<ul class="navbar-nav ml-md-auto">
				<li class="nav-item">
					<a href="{{ route('logout') }}" class="navbar-nav-link">
						 Logout &nbsp;
						<i class="icon-switch2"></i>						
					</a>
				</li>
			</ul>
		</div>
	</div>
{{-- setting agar turun ke bawah --}}
<div style="padding-top: 35px"></div>
<script type="text/javascript">
	$(document).ready(function(){
			function clock() {
				var now = new Date();
				var secs = ('0' + now.getSeconds()).slice(-2);
				var mins = ('0' + now.getMinutes()).slice(-2);
				var hr = now.getHours();
				var Time = hr + ":" + mins + ":" + secs;
				document.getElementById("watch").innerHTML = Time;
				document.getElementById("watch2").innerHTML = Time;
				requestAnimationFrame(clock);
			}
			requestAnimationFrame(clock);
	});
</script>

