<?php
if (!function_exists('check_login')) { include("include.php"); }

// We need to collect the data for the dashboard

$smarty->assign('template', 'dashboard.tpl');
$smarty->assign('menu_active','dashboard');
$smarty->display('main.tpl');

?>
