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

function get_domain_settings($domain) {
	global $dbconnect;
	$host = $dbconnect->escapeSimple($domain);
	$recs = sql_query(
		"SELECT *, flags&1 as active
			FROM domains
			WHERE hostname = '$host'
		"
	);
	if (sizeof($recs) == 1) {
		return $recs[0];
	}
	return;
}

function get_forward( $host, $uri ) {
	global $_CONF;
  global $dbconnect;

	$settings = get_domain_settings($host);
	if (!$settings) {
		return default_forward();
	}

	switch ($settings['domain_type']) {
		case 'ignore_uri':
			return $settings['root_forward'];
			break;
		case 'forward':
			return $settings['root_forward'].$uri;
			break;
		case 'rule_based':
			return rule_based_forward($settings, $host, $uri);
			break;
		case 'url_based':
			return url_based_forward($settings, $host, $uri);
			break;
	}

}

function rule_based_forward($settings, $host, $uri) {
	$url = $settings['not_found'] ?? default_forward();

	$search = array('%hostname%', '%hostname_encoded%', '%uri%', '$uri_encoded%');
	$replace = array($host, urlencode($host), $uri, urlencode($uri));
	$url = str_replace($search, $replace, $url);

	return $url;
}

function url_based_forward($settings, $host, $uri) {
	global $dbconnect;
	$hostname = $dbconnect->escapeSimple($host);
	$eUri = $dbconnect->escapeSimple(strtolower($uri));
	$recs = sql_query("SELECT *, flags&1 as active
			FROM url_forwards
			WHERE hostname = '$hostname'
				AND url = '$eUri'
				AND flags&1");

	if (sizeof($recs) != 1) {
		return rule_based_forward($settings, $host, $uri);
	}

	return $recs[0]['forward'];
}

function default_forward() {
	return $_CONF['default_forward'] ?? 'https://www.google.com';
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
