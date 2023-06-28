<?php
if (!function_exists('check_login')) { include("include.php"); }

$smarty->assign('url', array(
    'forward'=>'http://',
    'hostname' => $_GET['hostname']
  ));
$smarty->assign('pagetitle', 'New URL Redirect');
$smarty->assign('template', 'edit-url.tpl');
$smarty->display('main.tpl');

?>
