<?php
include_once('auth.php');
include_once('include.php');

if(session_id() == ''){ session_start();}

if (is_user_logged_in()) {
  include('dashboard.php');
  include('domains.php');
  include('aliases.php');
  include('authed-routes.php');
} else {
  include('anonymous.php');
}
?>
