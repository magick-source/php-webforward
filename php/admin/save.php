<?php
if (!function_exists('check_login')) { include("include.php"); }

$fwdid = $_POST['id'];

$host = mysql_real_escape_string( $_POST['hostname'] );
$fwd	= mysql_real_escape_string( $_POST['forward'] );
$active = $_POST['active'] ? 1 : 0;

if (!$host || !$fwd) { get_error(); }

if ($fwdid) {
	$query = "UPDATE redirects
			SET hostname='$host',
					forward='$fwd',
					flags=flags-(flags&1)+$active
			WHERE id=$fwdid";
} else {
	$query = "INSERT INTO redirects
			SET hostname='$host',
					forward='$fwd',
					flags=$active";
}
$res = $dbconnect->query($query);
is_error($res);

?>
