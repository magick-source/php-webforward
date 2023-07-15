<?php
include_once('db_base.php');

class User extends dbBase {
  protected static function _TABLE(): string { return "users"; }

  public $id;
  public $username;
  protected $password;

  public static function create($username, $password): User {
    $pass_hash = password_hash($password, PASSWORD_BCRYPT);

    $user = static::insert(
      array('username'=>$username, 'password'=>$pass_hash)
    );

    return new User($user);
  }

  public static function get_if_valid($username,$password): User|false {
    list($user) = static::sql_query(
      "select * from users where username=?",
      array($username)
    );
    if ($user and password_verify($password, $user['password'])) {
      return new User($user);
    }

    return false;
  }

}
