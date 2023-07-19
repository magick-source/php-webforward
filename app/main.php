<?php

include_once('include.php');
include_once('router.php');
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

  get('/404', 'handle_redirect');

  any('/404', function() {
    global $router;

    error_log("someone is being naughty? [{$router->info()}]");
    handle_redirect();
  });
}

function handle_redirect() {
  global $router;
  global $_CONF;

  $hostname = $router->hostname;
  $path = $router->uri;

  $domain = Domain::find_one(['hostname' => $hostname]);
  if (!$domain) {
    $alias = Alias::find_one(['domain_alias' => $hostname]);
    if ($alias) {
      $domain = Domain::find_one(['hostname' => $alias->use_hostname]);
    }
  }

  if (!$domain) {
    redirect_with_defaults(null);
    return;
  }

  if ($path == '' || $path == '/') {
    redirect_with_defaults($domain->root_forward);
    return;
  }

  switch ($domain->domain_type) {
    case 'ignore_uri':
      $url = $domain->root_forward;
      break;
    case 'forward':
      $url = $domain->root_forward.$path;
      break;
    case 'rule_based':
      $url = rule_based_forward($domain, $router->hostname, $path);
      break;
    case 'url_based':
      url_based_forward($domain, $router->hostname, $path);
      break;
  }

  redirect_with_defaults($url);
  return;
}

function rule_based_forward($domain, $host, $uri) {
	$url = $domain->not_found;
  if (!$url) {
    return redirect_with_defaults(null);
  }

	$search = array(
		'%domain%',
		'%domain_encoded%',
		'%uri%',
		'$uri_encoded%',
	);
	$replace = array(
		$host,
		urlencode($host),
		$uri,
		urlencode($uri),

	);
	$url = str_replace($search, $replace, $url);

	return $url;
}

function url_based_forward($domain, $host, $uri) {
  $url = Url::find_one(['hostname' => $domain->hostname, url => $uri]);
  if ($url) {
    return $url->forward;
  }

  return rule_based_forward($domain, $host, $uri);
}


?>
