<?php

require ROOT.'app/mahasiswa/session.php';

if($login->is_loggedin()=="")
{
	redirect(site_url('login'));
}
else {
	$login->doLogout();
	redirect(site_url());
}
