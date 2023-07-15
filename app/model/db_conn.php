<?php
if (!function_exists('get_db_connection')) {
include('DB.php');

global $_CONF;

$dsn = $_CONF['db_type'] . '://'
      . $_CONF['db_user'] . ':'
      . $_CONF['db_pass'] . '@'
      . $_CONF['db_host'] . '/'
      . $_CONF['db_db'];

$dbconn = null;

function get_db_connection() {
  global $dbconn;
  global $dsn;
  if ($dbconn == null) {
    $dbconn = DB::connect($dsn);
    if (DB::isError($dbconn)) {
      die("Database Error: ". DB::errorMessage($dbconn));
    }
  }

  return $dbconn;
}

}
?>
