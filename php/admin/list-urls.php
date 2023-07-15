<?php
if(!function_exists('check_login')) { include("include.php"); }

$domain = $_GET['domain'];

if ($domain) {}
  $smarty->assign("url_list",
  		get_url_list($domain)
  	);
}

$smarty->assign('pageroot', './urls.php');
$smarty->display('main.tpl');
?>
