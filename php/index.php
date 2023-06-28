<?php
if(!function_exists("get_forward")) { include("include.php"); }

$uri_parts = parse_url($_SERVER['REQUEST_URI']);
$fwd = get_forward( $_SERVER['HTTP_HOST'], $uri_parts['path'] );

header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
header('Location:'. $fwd);

?>
