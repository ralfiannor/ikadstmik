<?php
    require_once("class/session.php");    
    require_once("class/user.php");
    $auth_user = new USER();    
    $user_id = $_SESSION['user_session'];

    $stmt = $auth_user->runQuery("SELECT * FROM mahasiswa WHERE id=:user_id");
    $stmt->execute(array(":user_id"=>$user_id));
    
    $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Beranda Mahasiswa - IKAD STMIK</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="IKD STMIK Banjarbaru" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="../assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="../assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="../assets/css/font-awesome.css" rel="stylesheet"> 
<script src="../assets/js/jquery.min.js"> </script>
<!-- Data Table -->
<link href="../assets/datatables/css/dataTables.uikit.min.css" rel='stylesheet' type='text/css' />

<!--scrolling js-->
    <script src="../assets/js/jquery.nicescroll.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <!--//scrolling js-->
    <script src="../assets/js/bootstrap.min.js"> </script>

<!-- Mainly scripts -->
<script src="../assets/js/jquery.metisMenu.js"></script>
<script src="../assets/js/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<link href="../assets/css/custom.css" rel="stylesheet">
<script src="../assets/js/custom.js"></script>
<script src="../assets/js/screenfull.js"></script>
        <script>
        $(function () {
            $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

            if (!screenfull.enabled) {
                return false;
            }

            

            $('#toggle').click(function () {
                screenfull.toggle($('#container')[0]);
            });
            

            
        });
        </script>

<!----->

<!--skycons-icons-->
<script src="../assets/js/skycons.js"></script>
<!--//skycons-icons-->
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
               <h1><a class="navbar-brand" href="home.php">STMIK BJB</a></h1>         
               </div>
             <div class=" border-bottom">
            <div class="full-left">
              <section class="full-top">
                <button id="toggle"><i class="fa fa-arrows-alt"></i></button>   
            </section>
            <form class=" navbar-left-right">
              <input type="text"  value="Search..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search...';}">
              <input type="submit" value="" class="fa fa-search">
            </form>
            <div class="clearfix"> </div>
           </div>
     
       
            <!-- Brand and toggle get grouped for better mobile display -->
         
           <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="drop-men" >
                <ul class=" nav_1">
                   
                    <li class="dropdown at-drop">
                      <a href="#" class="dropdown-toggle dropdown-at " data-toggle="dropdown"><i class="fa fa-globe"></i> <span class="number">5</span></a>
                      <ul class="dropdown-menu menu1 " role="menu">
                        <li><a href="#">
                       
                            <div class="user-new">
                            <p>New user registered</p>
                            <span>40 seconds ago</span>
                            </div>
                            <div class="user-new-left">
                        
                            <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="clearfix"> </div>
                            </a></li>
                        <li><a href="#">
                            <div class="user-new">
                            <p>Someone special liked this</p>
                            <span>3 minutes ago</span>
                            </div>
                            <div class="user-new-left">
                        
                            <i class="fa fa-heart"></i>
                            </div>
                            <div class="clearfix"> </div>
                        </a></li>
                        <li><a href="#">
                            <div class="user-new">
                            <p>John cancelled the event</p>
                            <span>4 hours ago</span>
                            </div>
                            <div class="user-new-left">
                        
                            <i class="fa fa-times"></i>
                            </div>
                            <div class="clearfix"> </div>
                        </a></li>
                        <li><a href="#">
                            <div class="user-new">
                            <p>The server is status is stable</p>
                            <span>yesterday at 08:30am</span>
                            </div>
                            <div class="user-new-left">
                        
                            <i class="fa fa-info"></i>
                            </div>
                            <div class="clearfix"> </div>
                        </a></li>
                        <li><a href="#">
                            <div class="user-new">
                            <p>New comments waiting approval</p>
                            <span>Last Week</span>
                            </div>
                            <div class="user-new-left">
                        
                            <i class="fa fa-rss"></i>
                            </div>
                            <div class="clearfix"> </div>
                        </a></li>
                        <li><a href="#" class="view">View all messages</a></li>
                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret">Mahasiswa<i class="caret"></i></span><img src="../assets/images/in.jpg"></a>
                      <ul class="dropdown-menu " role="menu">
                        <li><a href="profile.html"><i class="fa fa-user"></i>Edit Profile</a></li>
                        <li><a href="inbox.html"><i class="fa fa-envelope"></i>Inbox</a></li>
                        <li><a href="calendar.html"><i class="fa fa-calendar"></i>Calender</a></li>
                        <li><a href="inbox.html"><i class="fa fa-clipboard"></i>Tasks</a></li>
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
                        <a href="home.php" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Beranda</span> </a>
                    </li>                   
                    <li>
                        <a href="isi_kuesioner.php" class=" hvr-bounce-to-right"><i class="fa fa-pencil-square nav_icon "></i><span class="nav-label">Isi Kuesioner</span> </a>
                    </li>                   
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon"></i> <span class="nav-label">Pengaturan</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="ganti_sandi.php" class=" hvr-bounce-to-right"> <i class="fa fa-key nav_icon"></i>Ganti Kata Sandi</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-link nav_icon"></i> <span class="nav-label">Link Terkait</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="signin.html" class=" hvr-bounce-to-right"><i class="fa fa-mortar-board nav_icon"></i>Web STMIK Banjarbaru</a></li>
                           <li><a href="signup.html" class=" hvr-bounce-to-right"><i class="fa fa-edge nav_icon"></i>E-Learning</a></li>
                            <li><a href="signup.html" class=" hvr-bounce-to-right"><i class="fa fa-facebook-square nav_icon"></i>Grup Facebook</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="logout.php?logout=true" class=" hvr-bounce-to-right"><i class="fa fa-sign-out nav_icon"></i> <span class="nav-label">Logout</span> </a>
                    </li>
                </ul>
            </div>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
       <div class="content-main">
