<?php

get('/', function() {
  build_page(
    'dashboard/home.tpl',
    array(
      'menu_active' => 'dashboard',
      'pagetitle'   => 'Home',
    ),
  );
});

?>
