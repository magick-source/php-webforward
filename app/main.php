<?php

include_once('include.php');
include_once('router.php');

function is_admin($router) {
  global $_CONF;
  return($_CONF['realhost'] && $router->hostname === $_CONF['realhost']);
}

try {
  routes();
} catch (Exception $e) {
  error_log($e);
  build_page('500.tpl', array(), array('layout'=>'public.tpl'));
}

function routes() {
  mount_if('is_admin', '/admin', 'admin/main.php');

  get('/404', 'handle_redirect');

  any('/404', function() {
    global $router;

    error_log("someone is being naughty? [{$router->info()}]");
    handle_redirect();
  });
}

function handle_redirect() {
  global $router;

  echo "Going to handle the redirect for {$router->hostname}-{$router->uri}";
}

?>
