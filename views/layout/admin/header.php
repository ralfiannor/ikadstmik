<!DOCTYPE HTML>
<html>
<head>
<title>Admin Panel IKAD STMIK Banjarbaru</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="IKD STMIK Banjarbaru" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="<?= site_url('assets/css/font-awesome.css') ?>" rel="stylesheet"> 
<link href="<?= site_url('assets/css/style.css') ?>" rel='stylesheet' type='text/css' />

<!-- Data Table -->
<link href="<?= site_url('assets/css/datatable/bootstrap.min.css') ?>" rel='stylesheet' type='text/css' />

<!-- Custom and plugin javascript -->
<link href="<?= site_url('assets/css/custom.css') ?>" rel="stylesheet">

</head>
<body>
<div id="wrapper">
        <nav class="navbar-default navbar-static-top" role="navigation">
             <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <h1><a class="navbar-brand" href="home.php">STMIK <br>BANJARBARU</a></h1>         
               </div>
             <div class=" border-bottom">
            <div class="full-left">
              <section class="full-top">
                <button id="toggle"><i class="fa fa-arrows-alt"></i></button>   
            </section>
            <form class="navbar-left-right">
              APLIKASI PENILAIAN KINERJA DOSEN STMIK BANJARBARU
            </form>
            <div class="clearfix"> </div>
           </div>
     
       
            <!-- Brand and toggle get grouped for better mobile display -->
         
           <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="drop-men" >
                <ul class=" nav_1">
                   
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret">Admin<i class="caret"></i></span><img src="<?= site_url('assets/images/in.jpg') ?>"></a>
                      <ul class="dropdown-menu " role="menu">
                        <li><a href="<?= site_url('admin/layanan/') ?>"><i class="fa fa-cogs"></i>Pengaturan</a></li>
                        <li><a href="<?= site_url('admin/ganti-sandi/') ?>"><i class="fa fa-key"></i>Ganti Kata Sandi</a></li>
                        <li><a href="<?= site_url('admin/logout') ?>"><i class="fa fa-sign-out"></i>Logout</a></li>
                      </ul>
                    </li>
                   
                </ul>
             </div><!-- /.navbar-collapse -->
            <div class="clearfix">       
     </div>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">                
                    <li>
                        <a href="<?= site_url('admin/beranda') ?>" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Beranda</span> </a>
                    </li>                   
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i> <span class="nav-label">Data Master</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?= site_url('admin/mahasiswa') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-users nav_icon"></i>Data Mahasiswa</a></li>
                            <li><a href="<?= site_url('admin/dosen') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-user-secret nav_icon"></i>Data Dosen</a></li>           
                            <li><a href="<?= site_url('admin/matakuliah') ?>" class=" hvr-bounce-to-right"><i class="fa fa-book nav_icon"></i>Data Matakuliah</a></li>           
                            <li><a href="<?= site_url('admin/dosen-ampu') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-user-md nav_icon"></i>Data Dosen Ampu</a></li>           
                            <li><a href="<?= site_url('admin/kategori-kuesioner') ?>" class=" hvr-bounce-to-right"><i class="fa fa-folder-open nav_icon"></i>Data Kategori</a></li>
                            <li><a href="<?= site_url('admin/kuesioner') ?>" class=" hvr-bounce-to-right"><i class="fa fa-stack-exchange nav_icon"></i>Data Kuesioner</a></li>
                       </ul>
                    </li>
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-sticky-note-o nav_icon"></i> <span class="nav-label">Data Transaksi</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?= site_url('admin/krs') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-street-view nav_icon"></i>KRS</a></li>
                            <li><a href="<?= site_url('admin/aktivitas-dosen') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-university nav_icon"></i>Aktivitas Dosen</a></li>
                       </ul>
                    </li>

                     <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-desktop nav_icon"></i> <span class="nav-label">Monitoring</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?= site_url('admin/monitoring') ?>" class=" hvr-bounce-to-right"><i class="fa fa-question-circle nav_icon"></i>Hasil Penilaian IKAD</a></li>
                       </ul>
                    </li>
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-clipboard nav_icon"></i> <span class="nav-label">Laporan IKAD</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?= site_url('admin/laporan-kuesioner/?mahasiswa') ?>" class=" hvr-bounce-to-right"><i class="fa fa-mortar-board nav_icon"></i>Kuesioner Mahasiswa</a></li>
                            <li><a href="<?= site_url('admin/laporan-kuesioner/?dosen') ?>" class=" hvr-bounce-to-right"><i class="fa fa-user-secret nav_icon"></i>Kuesioner Dosen</a></li>
                            <li><a href="<?= site_url('admin/laporan-aktivitas/') ?>" class=" hvr-bounce-to-right"><i class="fa fa-users nav_icon"></i>Aktivitas Dosen</a></li>
                            <li><a href="<?= site_url('admin/laporan-ikad/') ?>" class=" hvr-bounce-to-right"><i class="fa fa-university nav_icon"></i>Hasil IKAD</a></li>
                            <li><a href="<?= site_url('admin/rekapitulasi-ikad/') ?>" class=" hvr-bounce-to-right"><i class="fa fa-trophy nav_icon"></i>Rekapitulasi Hasil IKAD</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-link nav_icon"></i> <span class="nav-label">Link Terkait</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="http://stmik-banjarbaru.ac.id" class=" hvr-bounce-to-right"><i class="fa fa-mortar-board nav_icon"></i>Web STMIK Banjarbaru</a></li>
                           <li><a href="signup.html" class=" hvr-bounce-to-right"><i class="fa fa-edge nav_icon"></i>E-Learning</a></li>
                            <li><a href="signup.html" class=" hvr-bounce-to-right"><i class="fa fa-facebook-square nav_icon"></i>Grup Facebook</a></li>
                        </ul>
                    </li>

                     <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-cogs nav_icon"></i> <span class="nav-label">Pengaturan</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="<?= site_url('admin/ganti-sandi') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-key nav_icon"></i>Ganti Kata Sandi</a></li>
                            <li><a href="<?= site_url('admin/layanan') ?>" class=" hvr-bounce-to-right"> <i class="fa fa-windows nav_icon"></i>Layanan Aplikasi</a></li>
                       </ul>
                    </li>
                    
                    <li>
                        <a href="<?= site_url('admin/logout') ?>" class=" hvr-bounce-to-right"><i class="fa fa-sign-out nav_icon"></i> <span class="nav-label">Logout</span> </a>
                    </li>
                </ul>
            </div>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
       <div class="content-main">
