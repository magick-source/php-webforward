<?php
if (!function_exists('check_login')) { include("include.php"); }

$urlid = $_GET['id'];
if (!preg_match('#^\d+$#', $urlid)) { get_error(); }

$res = $dbconnect->query(
  "SELECT *, flags&1 as active FROM url_forwards WHERE id=$urlid"
);
is_error($res);

if ($res->numRows() != 0) {
  $smarty->assign('url', $res->fetchRow(DB_FETCHMODE_ASSOC));
  $smarty->assign('pagetitle', 'Edit URL');
  $smarty->assign('template', 'edit-url.tpl');
  $smarty->display('main.tpl');
} else {
  get_error();
}


?>
