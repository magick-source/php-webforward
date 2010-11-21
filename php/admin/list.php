<?php
if(!function_exists('check_login')) { include("include.php"); }

$smarty->assign("forwardlist",
		sql_query("SELECT id, hostname, forward, flags&1 active
			FROM redirects
			ORDER BY hostname ".
			limit()
		)
	);
pages("SELECT id FROM redirects");

$smarty->assign('pagetitle', 'List');
$smarty->assign('template', 'list.tpl');
$smarty->assign('pageroot', './index.php');
$smarty->display('main.tpl');
?>
