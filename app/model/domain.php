<?php
include_once('db_base.php');

class Domain extends dbBase {
  protected static function _TABLE(): string { return "domains"; }

  public $id;
  public string $hostname;
  public string $domain_type;
  public string $root_forward;
  public string $not_found;
  public $flags;
  public bool $active;
  public int $alias_count;
  public int $url_count;

  public function __construct($data) {
    parent::__construct($data);
    $this->active ??= $this->flags&1;
  }

  public static function get_list(int $page) {
    return static::sql_query_objects("
      SELECT
        d.id,
        d.hostname,
        domain_type,
        root_forward,
        not_found,
        d.flags,
        count(da.id) as alias_count,
        count(u.id) as url_count,
        d.flags&1 as active
      FROM domains as d
        LEFT JOIN domain_aliases as da
          ON d.hostname = da.use_hostname
        LEFT JOIN url_forwards u
          ON d.hostname = u.hostname
      GROUP by d.id
      ORDER by hostname
    ".static::limit($page));
  }

}

?>
