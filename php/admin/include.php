<?php
include('../include.php');

if ($_CONF['realhost'] and $_CONF['realhost'] !== $_SERVER['HTTP_HOST']) {
  include('../index.php');
  exit;
}

include("Smarty.class.php");
$smarty = new Smarty;
$smarty->template_dir = __DIR__."/../../templates";
$smarty->compile_dir = __DIR__."/../../templates_c";

check_login();

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
    header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
    header('Location: /admin/');
    exit;

	} else {
		login_page();
	}
}

function login_page() {
	global $smarty;

	$smarty->assign('pagetitle','Login');
	$smarty->assign('template','login.tpl');
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
		if ($_CONF['db_type'] == 'mysql') {
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
	$smarty->assign('pagecount', $pages[count($pages)-1]);
}

function get_error() {
	die( 'There is an error on the parameters' );
}

?>
