<?php

require ROOT.'app/admin/session.php';

if($login->is_loggedin()=="")
{
	redirect(site_url('admin/login'));
}
else {
	$login->doLogout();
	redirect(site_url('admin/'));
}
