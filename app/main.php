<?php

include_once('include.php');
include_once('admin/include.php');
include_once('router.php');
include_once('redirect.php');
include_once('app/model/alias.php');
include_once('app/model/domain.php');
include_once('app/model/url.php');

function is_admin($router) {
  global $_CONF;
  return($_CONF['realhost'] && $router->hostname === $_CONF['realhost']);
}

try {
  routes();
} catch (Exception $e) {
  error_log("Catching the exception! Exceptional!!");
  error_log($e);
  build_page('500.tpl', array(), array('layout'=>'public.tpl'));
}

function routes() {
  mount_if('is_admin', '/admin', 'admin/main.php');

  mount('/api', 'api.php');

  get('/404', 'handle_redirect');

  any('/404', function() {
    global $router;

    error_log("someone is being naughty? [{$router->info()}]");
    handle_redirect();
  });
}

?>
