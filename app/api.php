<?php

include_once('router.php');
include_once('app/model/domain.php');
include_once('app/model/alias.php');
include_once('app/model/token.php');

function check_token(): bool {
  $token_str = $_SERVER['HTTP_FWD_AUTH_TOKEN'] ?? null;
  if (!$token_str) {
    $token_str = $_GET['token'];
    if ($token_str) {
      error_log("Getting auth token as a GET param");
      header('wfwd_warning: Use Fwd-Auth-Token for authentication, please');
    }
  }
  if (!$token_str) {
    return false;
  }
  $token = Token::check_token($token_str);
  if ($token and $token->active) {
      return true;
  } else {
      return false;
  }
}

function api_error($code=404, $error="Not Found") {
  http_response_code($code);
  echo json_encode(["error" => $error]);
  exit;
}

try {
get('/v1/domains', function(){
  if (!check_token()) {
    api_error();
  }
  $domains = Domain::get_list(-1);
  $aliases = Alias::get_list_all(-1);

  $result = [];
  foreach ($domains as $domain) {
    $result[] = ['type' => 'domain', 'hostname' => $domain->hostname];
  }
  foreach ($aliases as $alias) {
    $result[]= [
      'type' => 'alias',
      'hostname' => $alias->domain_alias,
      'alias_of' => $alias->use_hostname
    ];
  }

  echo json_encode($result);
});


get('/404', function() {
  api_error();
});

} catch(Exception $e) {
  error_log("Got an API exception: {$e}");
  api_error(500, "API Error");
}

?>
