<?php
include_once('db_base.php');

class Token extends dbBase {
  protected static function _TABLE(): string { return 'api_tokens'; }

  public $id;
  public string $name;
  public string $token;
  public int $user_id;
  public $created_at;
  public $expires_at;
  public $cancelled_at;
  public int $flags;
  public bool $active;

  private string $token_base;

  public function __construct($data) {
    parent::__construct($data);
    $this->active ??= $this->flags&1;
  }

  public function expire() {
    $id = $this->id;
    static::sql_query(
      "UPDATE api_tokens
        SET flags=(flags-(flags&3)+2),
            cancelled_at=CURRENT_TIMESTAMP()
        WHERE id=?",
      [$id]
    );
    return static::get($this->id);
  }

  public static function get_list(int $page) {
    return static::sql_query_objects("
      SELECT t.*, username
        FROM api_tokens t
          LEFT JOIN users u
            ON t.user_id = u.id
        ORDER by name
    ".static::limit($page));
  }

  public static function generate_token(string $name): array|false {
    global $_CONF;
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    error_log("Going to insert token");
    static::sql_query(
      "INSERT INTO api_tokens
        SET name = ?,
          token = ?,
          user_id = ?,
          created_at = CURRENT_TIMESTAMP(),
          expires_at = ADDDATE(CURRENT_TIMESTAMP(), INTERVAL 1 YEAR),
          flags=1",
      [$name, md5($token), $_SESSION['user_id']]
    );
    error_log("Inserted the token!");
    list($rec) = static::sql_query("SELECT LAST_INSERT_ID() as last_id");
    if ($token_id = $rec['last_id']) {
      $rtoken = static::sql_query_objects(
          "SELECT * FROM api_tokens WHERE id = ?", [$token_id]
        );

      $key = hexdec(substr(md5($_CONF['token_key']), 0, 8));
      $sid = $token_id ^ $key;
      $hid = dechex($token_id ^ $key);
      error_log("token = { id => {$token_id}, key => $key, sid => {$sid} }");

      $token_str = "fwd-{$hid}-{$token}";

      return [
        'token' => $token_str,
        'name'  => $name,
      ];
    }
    return false;
  }

  public static function check_token(string $token_str): false | Token {
    global $_CONF;
    list($xx, $hid, $token) = explode('-', $token_str);

    $key = hexdec(substr(md5($_CONF['token_key']), 0, 8));
    $did = hexdec($hid);
    $id = $did^$key;

    $rtoken = static::find_one([
      'id' => $id,
      'token' => md5($token),
    ]);

    return $rtoken ?? false;
  }
}

?>
