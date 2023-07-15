<?php
// Here users are not authenticated
include_once('include.php');
include_once('app/utils/notifications.php');

function login_page($data=array()) {
  build_page(
    'login.tpl',
    array_merge(
      $data,
      array(
        'pagetitle' => 'Login'
      ),
    ),
    array(
      'layout' => 'public.tpl',
    )
  );
}

get('/404', 'login_page');

post('/404', function() {
  $data = login_form_data();
  if (check_login($data)) {
    $user = User::get_if_valid($data['username'], $data['password']);
    if ($user) {
      NOTIFY_SUCCESS("Login successful!");
      $_SESSION['user_id'] = $user->id;
      return redirect('/admin/');
    } else {
      NOTIFY_ERROR('Login failed - username or password invalid');
    }
  }

  login_page(array(
    "login_username" => $data['username'],
    "login_password" => $data['password'],
  ));
});

function login_form_data() {
  $data = array();
  if (isset($_POST['username']) && strlen($_POST['username'])) {
    $data['username'] = $_POST['username'];
  }
  if ($_POST['password'] && strlen($_POST['password'])) {
    $data['password'] = $_POST['password'];
  }

  return $data;
}

function check_login($data) {
  $errors = array();
  if (isset($data['username'])) {
    if (!preg_match('#^\w+$#', $data['username'])) {
      $errors[] = 'Invalid Username';
    }
  } else {
    $errors[] = 'Username is required';
  }

  if (!isset($data['password'])) {
    $errors[] = 'Password is required';
  }

  if (sizeof($errors)>0) {
    foreach ($errors as $error) {
      NOTIFY_ERROR($error);
    }
    return false;
  }

  return true;
}
