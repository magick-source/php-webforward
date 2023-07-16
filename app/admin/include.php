<?php

include_once("Smarty.class.php");
include_once("app/utils/notifications.php");

global $smarty;
$smarty = new Smarty();
$smarty->template_dir = __DIR__."/../../templates";
$smarty->compile_dir = __DIR__."/../../templates_c";
init_template_vars();

$forms = array('none' => array());

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
			'url' => 'domains/',
			'title' => 'Domains',
			'icon' => 'chess-rook'
		),
		array(
			'id' => 'aliases',
			'url' => 'aliases/',
			'title' => 'Aliases',
			'icon'	=> 'file-import',
		),
		array(
			'id' => 'urls',
			'url' => 'urls/',
			'title' => 'URLs',
			'icon' => 'link'
		),
		array(
			'id' => 'tokens',
			'url' => 'tokens/',
			'title' => 'Tokens',
			'icon' => 'key'
		),
	]);
	$smarty->assign('menu_active','');
}

function get_page() {
	return
		(isset($_GET['page']) && is_integer($_GET['page']))
			? $_GET['page'] : 1;
}

function build_page($template, $variables = array(), $options = array()) {
  global $smarty;
  global $router;

  $smarty->assign('pagetitle', 'Error!');
	$smarty->assign('notifications', new Notifications());

  foreach ($variables as $name => $value) {
    $smarty->assign($name, $value);
  }

  $smarty->assign('template', $template);
  $layout = $options['layout'] ?? 'main.tpl';
  $smarty->display($layout);
}

?>
