<?php
session_start();

include("config.php");
set_include_path(get_include_path()
	.':'.$_CONF['peardb_path']
	.':'.$_CONF['smarty_path']
);

include('DB.php');
$dsn = $_CONF['db_type'] . '://' . $_CONF['db_user'] . ':'
		. $_CONF['db_pass']
		. '@' . $_CONF['db_host'] . '/' . $_CONF['db_db'];

$dbconnect = DB::connect($dsn);
if (DB::isError($dbconnect)) {
	die("Database error: ". DB::errorMessage($dbconnect));
}

function get_forward( $host ) {
	global $_CONF;
  global $dbconnect;

	$host = $dbconnect->escapeSimple( $host );

	$res = sql_query(
		"SELECT forward
			FROM redirects
			WHERE hostname='$host'
				AND flags&1"
	);

	$fwd = $res[0]['forward'];
	if (!$fwd) {
		$fwd = $_CONF['default_forward'];
	}
	if (!$fwd) {
		$fwd = 'http://www.google.com/';
	}

	return $fwd;
}

function sql_query($query) {
	global $dbconnect;

	$return_array = array();
	$res = $dbconnect->query($query);
	is_error($res);
	while ($row = $res->fetchrow(DB_FETCHMODE_ASSOC)) {
		array_push($return_array, $row);
	}
	return $return_array;
}

function is_error($resource) {
	if (PEAR::isError($resource)) {
		die(
			$resource->getMessage()
			.'<br><br>'
			.$resource->getDebugInfo()
		);
	}
}

?>
