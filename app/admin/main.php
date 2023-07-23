<?php
include_once('auth.php');
include_once('include.php');

if(session_id() == ''){ session_start();}

if (is_user_logged_in()) {
  include('dashboard.php');
  include('domains.php');
  include('aliases.php');
  include('urls.php');
  include('tokens.php');

  get('/404', function () {
    http_response_code(404);
    build_page('404.tpl', array(), array('layout'=>'public.tpl'));
  });
} else {
  include('anonymous.php');
}
?>
