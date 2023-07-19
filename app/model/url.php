<?php

include_once('db_base.php');

class Url extends dbBase {
  protected static function _TABLE(): string { return "url_forwards"; }

  public $id;
  public string $hostname;
  public string $url;
  public string $forward;
  public int $flags;
  public bool $active;

  public function __construct($data) {
    parent::__construct($data);
    $this->active ??= $this->flags&1;
  }

  public static function get_list(string $domain, int $page) {
    return static::sql_query_objects("
      SELECT * FROM url_forwards WHERE hostname=?
    ".static::limit($page), array($domain));
  }

  public static function get_list_pages(...$params):int {
    $domain = $params[0];
    return static::nr_pages(
      "SELECT count(id) FROM url_forwards WHERE hostname=?",
      array($domain)
    );
  }
}

?>
