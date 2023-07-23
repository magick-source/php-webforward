<?php
if (!function_exists('get_db_connection')) {

class DB_Conn {
  static null|mysqli $db_conn;

  static function db_connection(): mysqli {
    global $_CONF;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    static::$db_conn ??= mysqli_connect(
      $_CONF['db_host'],
      $_CONF['db_user'],
      $_CONF['db_pass'],
      $_CONF['db_db']
    );

    return static::$db_conn;
  }
}

function get_db_connection(): mysqli {
  return DB_Conn::db_connection();
}

}
?>
