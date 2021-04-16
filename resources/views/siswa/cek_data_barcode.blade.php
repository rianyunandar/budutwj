

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="keywords" content="AKADEMIK SEKOLAH" />
  <meta name="description" content="AKADEMIK SEKOLAH SMK BUDI UTOMO WAY JEPARA" />
  <meta name="csrf-token" content="vREUFV3a3JSKSysMI6N5WyxW6cwTUsaiJXu66NSG">
  <title>Cek Data Siswa</title>

  <!-- Global stylesheets -->
  <link rel="shortcut icon" rel="icon" type="image/gif/png" href="https://budutwj.id/image/budut.png">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/global_assets/css/icons/material/icons.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/assets/css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/assets/css/components.min.css" rel="stylesheet" type="text/css">
  <link href="https://budutwj.id/assets/css/colors.min.css" rel="stylesheet" type="text/css">
  <link rel='stylesheet' href="https://budutwj.id/global_assets/js/plugins/datetimepicker/jquery.datetimepicker.css" />
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="https://budutwj.id/global_assets/js/main/jquery.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/main/bootstrap.bundle.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/loaders/blockui.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/ui/ripple.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/ui/sticky.min.js"></script>

  <!-- /core JS files -->

  <!-- Theme JS files -->
  
  <!-- pluginnya datatables-->
  <script src="https://budutwj.id/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/forms/selects/select2.min.js"></script>

  <!-- pluginnya form select-->
  <script src="https://budutwj.id/global_assets/js/plugins/forms/selects/select2.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
  <!-- pluginnya buat export -->
  <script src="https://budutwj.id/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

  <script src="https://budutwj.id/global_assets/js/plugins/notifications/jgrowl.min.js"></script>
  <script src="https://budutwj.id/global_assets/js/plugins/notifications/noty.min.js"></script>

  <script src="https://budutwj.id/assets/js/app.js"></script>
  <style type="text/css">
    .color_mryes{
      background-color: #05405a; 
      color: white;
    }
    .center-table-mryes{
      text-align: center;
    }
    .loading {
      position: absolute;
      left: 50%;
      top: 70%;
      transform: translate(-50%,-50%);
      font: 14px arial;
    }
    .loader {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      background: url('https://budutwj.id/global_assets/images/ajax-loader.gif') 50% 50% no-repeat rgb(249, 249, 249);
      opacity: .8;
    }
  </style>
  <!-- /theme JS files -->
  
</head>

<body>
  <div id='pesan'></div>
  <div class='loader'>
    <div class="loading">
     <p id="pesanku" >Proses...</p>
   </div>
 </div>

 
 <div style="padding-top: 35px"></div>

 <!-- /main navbar -->

 <!-- Alternative navbar -->
 
 <!-- /alternative navbar -->

 <!-- Page content -->
 <div class="page-content">

  <!-- /main sidebar -->
  <!-- Main content -->
  <div class="content-wrapper">
    <!-- Content area -->

    <div class="content">
      <!-- 2 columns form -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Cari SISWA</h5>
          <div class="header-elements">
            <div class="list-icons">
              <a class="list-icons-item" data-action="collapse"></a>
              <a class="list-icons-item" data-action="reload"></a>
              <a class="list-icons-item" data-action="remove"></a>
            </div>
          </div>
        </div>

        <div class="card-body">
          
          
          
          
          
          <div class="row "  >
            
            <div class="col-xl-3 col-sm-6">
              <div class="card">
                <div class="card-img-actions mx-1 mt-1" id="fotoprofile">
                  <img class="card-img img-fluid " src="https://budutwj.id/image/noimage.png?date=1617381701" alt=" ">
                  
                </div>
                <?php if($datasiswa->ssaIsActive==1){ $status ="AKTIF"; }
								else{ $status ="TIDAK AKTIF"; } ?>
                <div class="col-md-12 pt-3 pb-4" style='text-align: center; '>
                  <span  style='font-size: 2em' class='badge badge-success'>AKTIF</span>
                  <br><br>
                  <table style="width: 100%; font-size: 15px;">
										<tr>
											<td ><b>NISN</b></td>
											<td>:</td>
											<td style="text-align: left; color:red ;">{{ $datasiswa->profile_siswa->psNisn }}</td>
										</tr>
										<tr>
											<td ><b>NIS</b></td>
											<td>:</td>
											<td style="text-align: left; color:red; ">{{ $datasiswa->profile_siswa->psNis }}</td>
										</tr>
										
									</table>

                  
                </div>
                
              </div>
            </div>
            <div class="col-xl-9 col-sm-6">
							<!-- Account settings -->
							<div class="card">
								<div class="card-header header-elements-inline">
									<h5 class="card-title"><b>INFORMASI DATA SISWA</b></h5>
									<div class="header-elements">
										<div class="list-icons">
											<a class="list-icons-item" data-action="collapse"></a>
											<a class="list-icons-item" data-action="reload"></a>
										</div>
									</div>
								</div>
								<div class="card-body">
										<div class="form-group">
											<div class="row">
												<div class="col-md-3">
													<label><b>Username</b></label>
													<input disabled="disabled"  value="{{ $datasiswa->ssaUsername }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Agama</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaAgama  }}" type="text" class="form-control">
												</div>
												<div class="col-md-3">
													<label>Nama Depan</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaFirstName  }}" type="text" class="form-control">
												</div>
												<div class="col-md-4">
													<label>Nama Belakang</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->ssaLastName  }}" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-2">
													<label>NO HP</label>
													<input disabled="disabled"  value="{{ $datasiswa->ssaHp }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>NO WA</label>
													@if(empty($datasiswa->ssaWa))
													<input disabled name="namabelakang" type="text"  class="form-control">
													@else 
													<a class="form-control" style="color: blue" target="_blank" href="https://wa.me/{{ $datasiswa->ssaWa }}" >{{ $datasiswa->ssaWa }}</a>
													@endif
												</div>
												
												<div class="col-md-4">
													<label>Transportasi</label>
													<?php if(!empty($datasiswa->profile_siswa->psTransport)){ $transpot= $datasiswa->profile_siswa->transportasi->trsNama; }else{ $transpot="";} ?>
													<input disabled name="namabelakang" type="text" value="{{  $transpot  }}" class="form-control">
												</div>
												<div class="col-md-4">
													<label>Sekolah</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->master_sekolah->sklNama  }}" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													<label>Jurusan</label>
													<input disabled="disabled"  value="{{ $datasiswa->master_jurusan->jrsNama }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Rombel</label>
													<input disabled="disabled"  value="{{ $datasiswa->anggota_rombel->master_rombel->master_kelas->klsNama.' '.$datasiswa->anggota_rombel->master_rombel->rblNama }}" type="text" name="username" class="form-control">
												</div>
												<div class="col-md-2">
													<label>Tahun Angkatan</label>
													<input disabled name="namadepan" value="{{  $datasiswa->ssaTahunAngkata  }}" type="text" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label>ASAL SMP</label>
													<?php 
													if(empty($datasiswa->profile_siswa->psAsalSmp)){
														$smp = '-';
													}
													else{
														$smp = $datasiswa->profile_siswa->master_smp->smpNama;
													} 
													?>  
													
													<input disabled name="smp" value="{{ $smp }}" type="text" class="form-control">
												</div>
												<div class="col-md-6">
													<label>Prakerind</label>
													<input disabled name="namabelakang" type="text" value="{{  $datasiswa->ssaPrakerind  }}" class="form-control">
												</div>
											</div>
										</div>
								</div>
							</div>
							<!-- /account settings -->
						</div>
            
            
            <br><br>
            
          </div>  
          
          
        </div>
        <!-- /2 columns form -->
        <!-- /dashboard content -->
      </div>
      <!-- /content area -->
    </div>
    <div class="footer">
      <!-- Footer -->
      <style type="text/css">
        .footer-icon{
          font-size: 20px;
        }
        
      </style>

      <div class="navbar navbar-expand-lg navbar-light fixed-bottom ">
        <div class="text-center d-lg-none w-100 ">
          <div class="d-md-none">
            
            <div class="d-flex justify-content-center">
              <a href="https://budutwj.id/logout" class=" btn btn-link btn-float font-size-sm font-weight-semibold text-default">
                <i class="footer-icon icon-switch2 text-danger"></i>
                <span>LOGOUT</span>
              </a>
              <a href="https://budutwj.id/guru/guru-data-akun" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default">
                <i  class="footer-icon icon-reading text-success"></i>
                <span>AKUN</span>
              </a>
              <a href="https://budutwj.id/guru/home-guru" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default">
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
      <!-- /footer -->    </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
    
    <script src="https://budutwj.id/global_assets/js/demo_pages/navbar_multiple_sticky.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.loader').fadeOut('slow');
      });
    </script>
    
    <!-- Load plugin -->
    <script src="https://budutwj.id/global_assets/js/demo_pages/form_layouts.js"></script>

    <script src="https://budutwj.id/global_assets/js/demo_pages/form_select2.js"></script>
    <script src="https://budutwj.id/global_assets/js/demo_pages/components_popups.js"></script>


    <script src="https://budutwj.id/global_assets/js/demo_pages/components_popups.js"></script>
    <script src="https://budutwj.id/global_assets/js/demo_pages/extra_jgrowl_noty.js"></script>


    <script src="https://budutwj.id/global_assets/js/plugins/notifications/noty.min.js"></script>
    

  </body>
  </html>
