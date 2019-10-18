<?php

require ROOT.'app/dosen/session.php';

if($login->is_loggedin()=="")
{
	redirect(site_url('dosen/login'));
}
else {
	$login->doLogout();
	redirect(site_url('dosen/login'));
}
