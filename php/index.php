<?php
if(!function_exists("get_forward")) { include("include.php"); }

$fwd = get_forward( $_SERVER['HTTP_HOST'] );

header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
header('Location:'. $fwd.$_SERVER['REQUEST_URI']);

?>
