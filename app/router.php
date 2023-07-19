<?php

$router;
if (!isset($router)) init_router();

class RoutingInfo {
  public string $method;
  public string $uri;
  public string $hostname;
  public function __construct(
    string $method,
    string $hostname,
    string $uri
  ) {
    $this->method = $method;
    $this->hostname = $hostname;
    $this->uri = $uri;
  }
  public function info() {
    return "{$this->method} {$this->uri} [{$this->hostname}]";
  }
}

function init_router() {
  global $router;
  $router = new RoutingInfo(
      $_SERVER['REQUEST_METHOD'],
      $_SERVER['HTTP_HOST'],
      uri_with_no_query($_SERVER['REQUEST_URI']),
  );
}
function uri_with_no_query($uri): string {
  return explode('?', $uri, 2)[0];
}

function check_and_go($route, $check, $callback) {
  global $router;
  if (!is_callable($check)) {
    error_log("Invalid condition on route '$route'");
    exit();
  }

  if (call_user_func_array($check, [$router]))
    call_user_func($callback);
}

function mount_if($check, $route, $handler) {
  check_and_go($route, $check, function() use ($route,$handler) {
    mount($route, $handler);
  });
}

function mount($route, $handler) {
  global $router;
  if (
      substr($router->uri, 0, strlen($route)) == $route
      && substr($router->uri,strlen($route),1) == '/'
  ) {
    $old_router = $router;

    error_log("Routing to $handler");

    $rem_uri = substr($router->uri, strlen($route));
    $router = new RoutingInfo(
      $old_router->method,
      $old_router->hostname,
      $rem_uri
    );

    handle_route($handler, []);

    $router = $old_router;
  }
}

function any($route, $handler) {
  route($route,$handler);
}

function get($route, $handler) {
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    route($route, $handler);
  }
}

function post($route, $handler) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    route($route, $handler);
  }
}

function put($route, $handler) {
  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    route($route, $handler);
  }
}

function patch($route, $handler) {
  if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    route($route, $handler);
  }
}

function delete($route, $handler) {
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    route($route, $handler);
  }
}

function route($route, $handler) {
  global $router;
  if ($route === "/404") {
    handle_route($handler, []);
    exit();
  }

  $request_url = filter_var($router->uri, FILTER_SANITIZE_URL);
  $request_url = rtrim($request_url, '/');

  $route = rtrim($route, '/');

  $route_parts = explode('/', $route);
  $request_url_parts = explode('/', $request_url);
  array_shift($route_parts);
  array_shift($request_url_parts);

  if (sizeof($route_parts) && !sizeof($request_url_parts)) {
    return;
  }

  $params = [];
  for ($_i = 0; $_i < count($route_parts); $_i++) {
    $route_part = $route_parts[$_i];
    if (preg_match("/^[$]/", $route_part)) {
      $route_part = ltrim($route_part, '$');
      $$route_part = $request_url_parts[0];
      array_push($params, array_shift($request_url_parts));
    } else if (preg_match("/^[*]/", $route_part)) {
      if ($_i < count($route_parts)-1) {
        error_log("Invalid route '$route' - non-last greedy parameter");
        return;
      }
      $route_part = ltrim($route_part, '*');
      $$route_part = implode('/', $request_url_parts);
      array_push($params, $$route_part);
      $request_url_parts = [];

    } else if (isset($request_url_parts[0])
        && $route_parts[$_i] == $request_url_parts[0]) {
      array_shift($request_url_parts);

    } else {
      return;
    }
  }

  if (sizeof($request_url_parts)) {
    # the route didn't get all the url parts, so, no match!
    return;
  }

  handle_route($handler, $params);
  exit();
}

function handle_route($handler, $params) {
  if (is_callable($handler)) {
    call_user_func_array($handler, $params);
  } else {
    if (!strpos($handler, '.php')) {
      $handler .= '.php';
    }
    error_log("Going to include $handler");
    include_once __DIR__."/$handler";
  }
}

function redirect(string $url, string $code='302') {
  $code_name = array(
    '301' => 'Moved Permanently',
    '302' => 'Found',
    '303' => 'See other',
    '307' => 'Moved Temporarily',
  );
  header($_SERVER["SERVER_PROTOCOL"]." {$code} {$code_name[$code]}");
  header('Location:'. $url);
}

function redirect_with_defaults(string|null $url) {
  global $_CONF;
  redirect(
    $url
    ?? $_CONF['default_forward']
    ?? 'http://magick-source.net/'
  );
}

?>
