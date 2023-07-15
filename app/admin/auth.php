<?php

function is_user_logged_in() {
  if (isset($_SESSION['user_id'])) {
    $user = User::get($_SESSION['user_id']);
    if ($user) {
      return true;
    }
  }

  return false;
}

?>
