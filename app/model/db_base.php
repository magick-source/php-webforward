<?php

include_once('db_conn.php');

abstract class dbBase {

  abstract protected static function _TABLE(): string;
  protected static function _ID(): string { return "id"; }

  protected function __construct($data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  public static function insert($data = array()) {
    $table = static::_TABLE();

    $sets = array();
    $values = array();
    foreach ($data as $field => $value) {
      $sets[] = "{$field}=?";
      $values[] = $value;
    }
    if (!$sets) {
      $sdata = print_r($data, true);
      die("Don't know how to insert this: {$sdata}");
    }
    $set = join(', ', $sets);
    $query = "INSERT INTO {$table} SET {$set}";
    static::sql_query($query, $values);

    list($rec) = static::sql_query("select LAST_INSERT_ID() as last_id");

    if ($rec_id = $rec['last_id']) {
      list($rec) = static::sql_query_objects(
        "SELECT * FROM $table WHERE id=?",
        array($rec_id)
      );
    }

    return $rec;
  }

  public static function sql_query($query, $data = array()) {
    $db = get_db_connection();

    $res = $db->query($query, $data);
    if (DB::isError($res)) {
      die($res->getMessage());
    }

    if (is_integer($res)) {
      return $res;
    }

    $return_array = array();
    while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
      $return_array[] = $row;
    }
    return $return_array;
  }

  public static function sql_query_objects($query, $data = array()) {
    $res = static::sql_query($query, $data);
    if ($res) {
      $to_return = array();
      foreach ($res as $rec) {
        $to_return[] = new static($rec);
      }

      return $to_return;
    } else {
      return false;
    }
  }

  public static function get($id): dbBase|null {
    $id_field = static::_ID();
    $table = static::_TABLE();

    list($obj) = static::sql_query_objects(
      "select * FROM {$table} WHERE {$id_field}=?",
      $id
    );

    return $obj;
  }

  public static function find_one(array $where): dbBase|false {
    $table = static::_TABLE();

    $wfields = array();
    $wvalues = array();
    foreach ($where as $field => $value) {
      $wfields[]= "{$field}=?";
      $wvalues[]= $value;
    }
    $query = "SELECT * FROM {$table} WHERE ".implode(' AND ', $wfields);

    $recs = static::sql_query($query, $wvalues);

    if (sizeof($recs) == 1) {
      return new static($recs[0]);
    }
    return false;
  }

  public static function limit(int $page): string {
    global $_CONF;
    $range = $_CONF['range'] ?? 0;
    if ($range > 0 && $page >= 0) {
      $limit = 'LIMIT '
        .(($page*$range)-$range)
        .','
        . $range;
    } else {
      $limit = '';
    }

    return $limit;
  }

  public static function get_list_pages(...$params): int {
    $table  = static::_TABLE();
    $id     = static::_ID();
    $query  = "SELECT count({$id}) from {$table}";

    return static::nr_pages($query);
  }

  protected static function nr_pages($query, $params=array()): int {
    global $_CONF;

    if (!($_CONF['range']>0)) {
      return 1;
    }

    list($count) = static::sql_query($query, $params);
    if (!is_integer($count)) {
      $str = _xx__toString($count);
      error_log("count: {$str}");
      list($count) = array_values($count);
    }

    return ceil($count/$_CONF['range']);
  }

  public function update(array $data): dbBase {
    $table  = $this->_TABLE();
    $id  = $this->_ID();

    $sets = array();
    $values = array();
    foreach ($data as $fld => $val) {
      if ($fld == $id)
        next; //we never want to update the id

      if (!preg_match("/^\w+$/", $fld)) {
        die;
      }
      $sets[] = "{$fld}=?";
      $values[] = $val;
    }

    $query = "UPDATE {$table} SET "
            . implode(', ', $sets)
            . " WHERE {$id}=?";

    error_log("Going to run the query:\n\t{$query}");
    $values[] = $this->$id;
    $this->sql_query($query, $values);

    return $this->get($this->$id);
  }

  public function delete() {
    $table  = $this->_TABLE();
    $id     = $this->_ID();

    $query = "DELETE FROM {$table} WHERE {$id}=?";
    $this->sql_query($query, array($this->$id));
  }

  public function __toString(): string {
    return _xx__toString($this);
  }

}

function _xx__toString($thing): string {
  $str = (is_object($thing) ? get_class($thing) : gettype($thing))."(";
  $values = array();
  foreach($thing as $key => $value) {
    $values[] = "'{$key}' => '{$value}'";
  }
  $str .= implode(', ', $values);
  $str .= ")";

  return $str;
}

?>

