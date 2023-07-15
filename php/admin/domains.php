<?php
if(!function_exists('check_login')) { include("include.php"); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_POST['type'] = 'domain';
	include('save.php');
}
if (isset($_GET['delete']) && $_GET['delete'] == 'y') {
	include('delete-domain.php');
}

global $smarty;
$smarty->assign('menu_active', 'domains');

include "list-domains.php";

?>
