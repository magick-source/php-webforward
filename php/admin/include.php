<?php
include('../include.php');

if ($_CONF['realhost'] and $_CONF['realhost'] !== $_SERVER['HTTP_HOST']) {
	$cfg_host = $_CONF['realhost'];
	$http_host = $_SERVER['HTTP_HOST'];
	error_log("Got hosts: $cfg_host => $http_host\n\n");
  include('../index.php');
  exit;
}

include("Smarty.class.php");
$smarty = new Smarty;
$smarty->template_dir = __DIR__."/../../templates";
$smarty->compile_dir = __DIR__."/../../templates_c";

check_login();

init_template_vars();

function check_login() {
	global $dbconnect;
	if (isset($_POST['username']) && isset($_POST['password'])) {
		if (!preg_match('#^\w+$#', $_POST['username'])) {
			die("Invalid username");
		}
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = md5($_POST['password']);
	}
	if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
		$res = $dbconnect->query(
			"SELECT id FROM users
				WHERE username = '".$_SESSION['username']
				. "' AND password = '".$_SESSION['password']."'"
		);
		if ($res->numRows() == 0) {
			login_page();
		}
    if (isset($_POST['username']) ) {
      header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
      header('Location: /admin/');
      exit;
    }

	} else {
		login_page();
	}
}

function get_list() {
	return (($_GET['domain'] ?? false)
		? get_url_list( $_GET['domain'] )
		: get_domain_list());
}

function get_domain_list() {
	global $smarty;
	$smarty->assign('pagetitle', 'List Domains');
	$smarty->assign('template', 'list-domains.tpl');

	pages("SELECT id from domains");
	return sql_query("SELECT
			d.id,
			d.hostname,
			domain_type,
			root_forward,
			not_found,
			count(da.id) as aliases_count,
			count(u.id) as url_count,
			d.flags&1 active
		FROM domains as d
			LEFT JOIN domain_aliases as da
				ON d.hostname = da.use_hostname
			LEFT JOIN url_forwards u
				ON d.hostname = u.hostname
		GROUP by d.id
		ORDER BY hostname ".
		limit()
	);
}

function get_url_list($domain) {
	global $dbconnect;
	$host = $dbconnect->escapeSimple($domain);

	$settings = get_domain_settings($domain);
	if (!($settings && $settings['domain_type'] == 'url_based')) {
		return error_page("This domain is not configured for URL Based redirects");
	}

	global $smarty;
	$smarty->assign('pagetitle', 'List URLs for <i>$host</i>');
	$smarty->assign('hostname', $domain);
	$smarty->assign('template', 'list-urls.tpl');

	pages("SELECT id FROM url_forwards where hostname = '$host'");
	return sql_query("
		SELECT
			id,
			hostname,
			url,
			forward,
			flags&1 as active
		FROM url_forwards
		WHERE hostname = '$host'
		ORDER by url
		".limit()
	);
}

function login_page() {
	global $smarty;

	$smarty->assign('pagetitle','Login');
	$smarty->assign('template','login.tpl');
	$smarty->display('main.tpl');
	die();
}

function error_page($error) {
	global $smarty;

	$smarty->assign('pagetitle', 'Error');
	$smarty->assign('error_message', $error);
	$smarty->assign('template', 'error.tpl');
	$smarty->display('main.tpl');
	die();
}

function limit() {
	global $_CONF; global $smarty;
	if (!isset($_GET['page'])) {
		$_GET['page'] = 1;
	}
	$smarty->assign('current_page', $_GET['page']);

	if ($_CONF['range'] > 0) {
		if ($_CONF['db_type'] == 'mysql' or $_CONF['db_type'] == 'mysqli') {
			$limit = 'LIMIT '
				. (($_GET['page'] * $_CONF['range']) - $_CONF['range'])
				. ', '
				. $_CONF['range'];
		} elseif ($_CONF['db_type'] == 'psql') {
			$limit = 'OFFSET '
				. (($_GET['page'] * $_CONF['range']) - $_CONF['range'])
				. ' LIMIT '
				. $_CONF['range'];
		} else {
			$limit = '';
		}
	} else {
		$limit = '';
	}

	return $limit;
}

function pages($sql) {
	global $_CONF; global $smarty;
	global $dbconnect;

	if (!($_CONF['range'] > 0 ) ) {
		return;
	}

	$pages = array();
	$count = $dbconnect->query($sql)->numRows();
	if ($count > $_CONF['range'] ) {
		for ($i = 1; $i <= ceil($count/$_CONF['range']); $i++ ){
			array_push($pages, $i);
		}
	}
	$smarty->assign('pages', $pages);
	$smarty->assign('pagecount', ceil($count/$_CONF['range']));
}

function get_error() {
	die( 'There is an error on the parameters' );
}

function init_template_vars() {
	global $smarty;

	$smarty->assign('menu_items', [
		array(
			'id' => 'dashboard',
			'url' => '',
			'title' => 'Dashboard',
			'icon' => 'tachometer-alt'
		),
		array(
			'id' => 'domains',
			'url' => 'domains.php',
			'title' => 'Domains',
			'icon' => 'chess-rook'
		),
		array(
			'id' => 'urls',
			'url' => 'urls.php',
			'title' => 'URLs',
			'icon' => 'link'
		),
		array(
			'id' => 'tokens',
			'url' => 'tokens.php',
			'title' => 'Tokens',
			'icon' => 'key'
		),
	]);
	$smarty->assign('menu_active','');

}

?>
