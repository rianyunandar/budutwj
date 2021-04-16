@extends('master_guru')
@section('title')
@section('content')

<?php $info = getInformasiSekolah(); ?>
<!-- Content area -->
<div class="content">  
    {{-- Menu 0 --}}
    {{-- <div class="row">
      <div class="col-md-12">
        <!-- Info alert -->
				<div class="alert alert-info alert-styled-left alert-arrow-left bg-white">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
					<h6 class="alert-heading font-weight-semibold mb-1">Session timeout</h6>
					With these settings, session timeout plugin launches a timeout warning dialog in a fixed amount of time regardless of user activity. In this demo warning dialog appears <strong>after 10 seconds</strong> of page load.
			    </div>
			    <!-- /info alert -->
      </div>
    </div>  --}}
    {{--Menu 2 informasi pengumuman , siswa , kelas Tab Menu  --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
            <li class="nav-item"><a href="#tab-desc" class="nav-link active" data-toggle="tab">Pengumumuman</a></li>
            <li class="nav-item"><a href="#tab-spec" class="nav-link" data-toggle="tab">Info Siswa</a></li>
            <li class="nav-item"><a href="#tab-shipping" class="nav-link" data-toggle="tab">Info Kelas</a></li>
          </ul>
          
          <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
            {{-- pengumuman --}}
            <div class="tab-pane fade show active" id="tab-desc">
              <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  @foreach ($info as $key=>$data)
                  <?php if($key==0){ $aktif = "active";  } else{ $aktif = ""; }?>
                    <div class="carousel-item {{ $aktif }}">
                      {{-- judul info --}}
                      <div class="row">
                        <div class="col-md-12 text-center">
                          <h2 class="mb-0 "><b>{{ $data->infoJudul }}</b></h2>
                          </div>
                        </div>
                      <hr class="mt-0">
                      {{-- isi info --}}
                      <div class="row">
                        <div class="col-md-12 ml-3 mr-3">
                          {!! $data->infoIsi !!}
                        </div>
                      </div>
                      <hr class="mt-0">
                      {{-- footer info --}}
                      <div class="row">
                        <div class="col-md-12">
                          <footer class="blockquote-footer">Di Terbitkan <cite title="Source Title">{{ $data->infoSlagNama }}</cite> Tanggal : {{ formatTanggalIndo($data->infoCreated) }}</footer>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="icon-chevron-left text-dark mr-5" style="font-size: 30px; "  aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Previous</span> --}}
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="icon-chevron-right text-dark ml-5" style="font-size: 30px; " aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Next</span> --}}
                </a>
               
              </div> {{-- end corousule --}}
            </div>

            {{-- info siswa--}}
            <div class="tab-pane fade" id="tab-spec">
              <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                  {{-- <div class="carousel-item active">
                    <!--judul info -->
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <h3 class="mb-0 ">Judul Info Ke 1</h3>
                        </div>
                      </div>
                    <hr class="mt-0">
                    <!-- isi info -->
                    <div class="row">
                      <div class="col-md-12">
                        <h5 class="font-weight-semibold">Technical details</h5>
                        <p>Launched by a <strong>Saturn V</strong> rocket from <a href="#">Kennedy Space Center</a> in Merritt Island, Florida on July 16, Apollo 11 was the fifth manned mission of <a href="#">NASA</a>'s Apollo program. The Apollo spacecraft had three parts:</p>
                        <ol>
                          <li><strong>Command Module</strong> with a cabin for the three astronauts which was the only part which landed back on Earth</li>
                          <li><strong>Service Module</strong> which supported the Command Module with propulsion, electrical power, oxygen and water</li>
                          <li><strong>Lunar Module</strong> for landing on the Moon.</li>
                        </ol>
                      </div>
                    </div>
                    <hr class="mt-0">
                    <!-- footer info -->
                    <div class="row">
                      <div class="col-md-12">
                        <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                      </div>
                    </div>
                  </div> --}}

                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="icon-chevron-left text-dark mr-5" style="font-size: 30px; "  aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Previous</span> --}}
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="icon-chevron-right text-dark ml-5" style="font-size: 30px; " aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Next</span> --}}
                </a>
               
              </div> {{-- end corousule --}}
            </div>
            {{-- info kelas--}}
            <div class="tab-pane fade" id="tab-shipping">
              <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                  {{-- <div class="carousel-item active">
                    <!--judul info -->
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <h3 class="mb-0 ">Judul Info Ke 1</h3>
                        </div>
                      </div>
                    <hr class="mt-0">
                    <!-- isi info -->
                    <div class="row">
                      <div class="col-md-12">
                        <h5 class="font-weight-semibold">Technical details</h5>
                        <p>Launched by a <strong>Saturn V</strong> rocket from <a href="#">Kennedy Space Center</a> in Merritt Island, Florida on July 16, Apollo 11 was the fifth manned mission of <a href="#">NASA</a>'s Apollo program. The Apollo spacecraft had three parts:</p>
                        <ol>
                          <li><strong>Command Module</strong> with a cabin for the three astronauts which was the only part which landed back on Earth</li>
                          <li><strong>Service Module</strong> which supported the Command Module with propulsion, electrical power, oxygen and water</li>
                          <li><strong>Lunar Module</strong> for landing on the Moon.</li>
                        </ol>
                      </div>
                    </div>
                    <hr class="mt-0">
                    <!-- footer info -->
                    <div class="row">
                      <div class="col-md-12">
                        <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                      </div>
                    </div>
                  </div> --}}

                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="icon-chevron-left text-dark mr-5" style="font-size: 30px; "  aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Previous</span> --}}
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="icon-chevron-right text-dark ml-5" style="font-size: 30px; " aria-hidden="true" ></span>
                  {{-- <span class="sr-only">Next</span> --}}
                </a>
               
              </div> {{-- end corousule --}}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{--Menu 3 --}}
    {{-- <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-dark text-white header-elements-inline">
            <h6 class="card-title">Dark header</h6>
            <div class="header-elements">
              <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="reload"></a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="list-feed">
              <div class="list-feed-item border-warning-400">
                <div class="text-muted font-size-sm mb-1">12 minutes ago</div>
                <a href="#">David Linner</a> requested refund for a double card charge 
              </div>

              <div class="list-feed-item border-warning-400">
                <div class="text-muted font-size-sm mb-1">12 minutes ago</div>
                User <a href="#">Christopher Wallace</a> is awaiting for staff reply
              </div>

              <div class="list-feed-item border-warning-400">
                <div class="text-muted font-size-sm mb-1">12 minutes ago</div>
                Ticket <strong>#43683</strong> has been closed by <a href="#">Victoria Wilson</a>
              </div>

              <div class="list-feed-item border-warning-400">
                <div class="text-muted font-size-sm mb-1">12 minutes ago</div>
                All sellers have received payouts for December!
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
    <br><br>
   
    
</div>
<!-- /Content area -->
@endsection
@push('js_atas')
@endpush
@push('js_bawah')
@endpush
@push('jsku')
<script>
  $('.carousel').carousel({
    interval: 4000
  });
</script>
@endpush