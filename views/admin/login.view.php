<?php

if($login->is_loggedin()!="")
{
    redirect(site_url('admin/beranda'));
}



if(isset($_POST['btn-login']))
{
	$uname = strip_tags($_POST['txt_username']);
	$upass = strip_tags($_POST['txt_password']);
    $redirect = NULL;
    if($_POST['location'] != '') {
        $redirect = $_POST['location'];
    }
    else {
        $redirect = "beranda/";
    
    }
	if($login->doLogin($uname,$upass))
	{
        header("Location:". $redirect);
	}
	else
	{
		$error = "Username dan password tidak cocok !";
	}	
}
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Admin Panel IKAD STMIK Banjarbaru</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="IKAD STMIK Banjarbaru" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="<?= site_url('assets/css/style.css')?>" rel='stylesheet' type='text/css' />
<link href="<?= site_url('assets/css/font-awesome.css') ?>" rel="stylesheet"> 
<script src="<?= site_url('assets/js/jquery.min.js') ?>"> </script>
<script src="<?= site_url('assets/js/bootstrap.min.js') ?>"> </script>
</head>
<body>
    <div class="login">
        <h1><a href="#">APLIKASI PENILAIAN KINERJA DOSEN<br>STMIK BANJARBARU</a></h1>
        <div class="login-bottom">
            <h2>Login Area Admin</h2>
        <form id="login_form" method="post">
            <div class="col-md-12">
        <?php
            if(isset($error))
            {
        ?>
                <div id="error">
                    <div class="alert alert-danger">
                       <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                    </div>
                </div>
        <?php
            }
        ?>
                <div class="login-mail">
                    <input type="hidden" name="location" value="<?=((isset($_GET['location']))?htmlspecialchars($_GET['location']):'')?>" >
                    <input type="text" id="login_username" name="txt_username" placeholder="Nama pengguna" required="">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="login-mail">
                    <input type="password" id="login_password" name="txt_password" placeholder="Kata sandi" required>
                    <i class="fa fa-lock"></i>
                </div>

            
            </div>
            <div class="col-md-6 login-do">
                <label class="hvr-shutter-in-horizontal login-sub">
                    <input type="submit" value="login" name="btn-login">
                    </label>
            </div>
            
            <div class="clearfix"> </div>
            </form>
        </div>
    </div>
        <!---->
<div class="copy-right">
            <p> &copy; 2017 IKAD STMIK Banjarbaru. All Rights Reserved | Develop by <a href="#">Rizal Alfiannor</a></p>
<!---->
<!--scrolling js-->
    <script src="<?= site_url('assets/js/jquery.nicescroll.js') ?>"></script>
    <script src="<?= site_url('assets/js/scripts.js') ?>"></script>
    <!--//scrolling js-->
</body>
</html>
