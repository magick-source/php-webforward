<?php
include_once('auth.php');
include_once('include.php');

if(session_id() == ''){ session_start();}

if (is_user_logged_in()) {
  include('dashboard.php');
  include('domains.php');
  include('aliases.php');
  include('urls.php');

  get('/404', function () {
    build_page('404.tpl', array(), array('layout'=>'public.tpl'));
  });
} else {
  include('anonymous.php');
}
?>
