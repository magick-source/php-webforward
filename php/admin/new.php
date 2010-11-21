<?php
if (!function_exists('check_login')) { include("include.php"); }

$smarty->assign('forward', array('forward'=>'http://'));
$smarty->assign('pagetitle', 'New Redirect');
$smarty->assign('template', 'edit.tpl');
$smarty->display('main.tpl');

?>
