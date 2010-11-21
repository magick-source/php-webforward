<?php
if (!function_exists('check_login')) { include("include.php"); }

$fwdid = $_GET['id'];
if (!preg_match('#^\d+$#', $fwdid)) { get_error(); }

$query = "DELETE FROM redirects
		WHERE id=$fwdid";
$res = $dbconnect->query($query);
is_error($res);

?>
