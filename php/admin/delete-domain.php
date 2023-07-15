<?php
if (!function_exists('check_login')) { include("include.php"); }

$fwdid = $_GET['id'];
if (!preg_match('#^\d+$#', $fwdid)) { get_error(); }

$query = "DELETE FROM domains
		WHERE id=$fwdid";
error_log("going to: $query");
$res = $dbconnect->query($query);
is_error($res);

?>
