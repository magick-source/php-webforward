<?php
if (!function_exists('check_login')) { include("include.php"); }

$smarty->assign('forward', array('forward'=>'http://'));
$smarty->assign('pagetitle', 'New Domain');
$smarty->assign('template', 'edit-domain.tpl');
$smarty->display('main.tpl');

?>
