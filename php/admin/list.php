<?php
if(!function_exists('check_login')) { include("include.php"); }

$smarty->assign("forwardlist",
		get_list()
	);

$smarty->assign('pageroot', './index.php');
$smarty->display('main.tpl');
?>
