<?php
if(!function_exists("is_admin")) { include("include.php"); }

$fwd = get_forward( $_SERVER['HTTP_HOST'] );

header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
header('Location:'. $fwd);


?>
