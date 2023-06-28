<?php
if (!function_exists('check_login')) { include("include.php"); }

$fwdid = $_GET['id'];
if (!preg_match('#^\d+$#', $fwdid)) { get_error(); }

$res = $dbconnect->query("SELECT *,flags&1 active FROM domains WHERE id = $fwdid");
is_error($res);
if ($res->numRows() != 0) {
	$smarty->assign('forward', $res->fetchRow(DB_FETCHMODE_ASSOC));
	$smarty->assign('pagetitle', 'Edit Redirect');
	$smarty->assign('template', 'edit-domain.tpl');
	$smarty->display('main.tpl');
} else {
	get_error(); //invalid redirect, parameter error!
}

?>
