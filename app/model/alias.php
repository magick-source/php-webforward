<?php
include_once('db_base.php');

class Alias extends dbBase {
  protected static function _TABLE(): string { return "domain_aliases"; }

  public $id;
  public string $domain_alias;
  public string $use_hostname;
  public int $flags;
  public bool $active;

  public function __construct($data) {
    parent::__construct($data);
    $this->active ??= $this->flags&1;
  }

  public static function get_list(string $domain, int $page) {
    return static::sql_query_objects("
      SELECT * FROM domain_aliases where use_hostname=?
    ".static::limit($page), array($domain));
  }

  public static function get_list_all() {
    return static::sql_query_objects("SELECT * FROM domain_aliases");
  }

  public static function get_list_pages(...$params):int {
    $domain = $params[0];
    return static::nr_pages(
      "SELECT count(id) FROM domain_aliases where use_hostname=?",
      array($domain)
    );
  }
}

?>
