<?php

set_include_path(get_include_path().':../');

include("config/config.php");

function is_static() {
  global $_CONF;
  $host = $_SERVER['HTTP_HOST'];
  $uri = $_SERVER['REQUEST_URI'];
  if ($host != $_CONF['realhost'])
    return false;

  return preg_match("/^\/static\//", $uri);
}

if (is_static()) {
  return false;
}

include("app/main.php");

?>
