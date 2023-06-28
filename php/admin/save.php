<?php
if (!function_exists('check_login')) { include("include.php"); }

if ($_POST['type'] === "domain") {
	save_domain();
} else if ($_POST['type'] === 'url') {
	save_url();
}

function save_domain() {
	global $dbconnect;
	$fwdid = $_POST['domain_id'];

	$host 		 = $dbconnect->escapeSimple( $_POST['hostname'] );
	$type 		 = $dbconnect->escapeSimple( $_POST['domain_type'] );
	$rfwd			 = $dbconnect->escapeSimple( $_POST['root_forward'] );
	$not_found = $dbconnect->escapeSimple( $_POST['not_found'] );
	$active 	 = $_POST['active'] ? 1 : 0;

	if (!$host || ($type != 'url_based' && !$rfwd)) { get_error(); }

	if ($fwdid) {
		$fwdid = $dbconnect->escapeSimple($fwdid);
		$query = "UPDATE domains
				SET hostname='$host',
						domain_type='$type',
						root_forward='$rfwd',
						not_found='$not_found',
						flags=flags-(flags&1)+$active
				WHERE id=$fwdid";
	} else {
		$query = "INSERT INTO domains
				SET hostname='$host',
						domain_type='$type',
						root_forward='$rfwd',
						not_found='$not_found',
						flags=$active";
	}
	$res = $dbconnect->query($query);
	is_error($res);
}

function save_url() {
	global $dbconnect;
	$urlid = $_POST['url_id'];

	$host = $dbconnect->escapeSimple( $_POST['hostname']);
	$url  = $dbconnect->escapeSimple( $_POST['url']);
	$forward = $dbconnect->escapeSimple( $_POST['forward']);
	$active = $_POST['active'] ? 1 : 0;

	if (!$host || !$url || !$forward) { get_error(); }

	if ($urlid) {
		$urlid = $dbconnect->escapeSimple($urlid);
		$query = "UPDATE url_forwards
			SET url='$url',
					forward='$forward',
					flags=flags-(flags&1)+$active
			WHERE id=$urlid
		";
	} else {
		$query = "INSERT INTO url_forwards
			SET hostname = '$host',
				url = '$url',
				forward = '$forward',
				flags = $active";
	}

	$res = $dbconnect->query($query);
	is_error($res);
}

?>
