<?php

// Include paths.
$_CONF['smarty_path']   = "/usr/share/php/smarty4";
$_CONF['peardb_path']   = "/usr/share/php";

// Database DSN.
$_CONF['db_type']       = "mysqli"; // mysql for MySQL, pgsql for PostgreSQL
$_CONF['db_user']       = "webforward";
$_CONF['db_pass']       = "redirect";
$_CONF['db_host']       = "localhost";
$_CONF['db_db']         = "webforward";

$_CONF['default_forward'] = 'http://www.google.com/?q=never+gona+give+you+up';

$_CONF['range']					= 50;

$_CONF['realhost']      = 'localhost:8000';

// Please, please, please set this to something else!
$_CONF['token_key']     = 'just-some-secret';

?>
