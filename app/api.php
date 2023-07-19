<?php

include_once('router.php');
include_once('app/model/domain.php');
include_once('app/model/alias.php');

function check_token(): bool {
  return true;
}


get('/v1/domains', function(){
  if (!check_token()) {
    throw new Exception("Page Not Found");
  }
  $domains = Domain::get_list(-1);
  $aliases = Alias::get_list_all(-1);

  $result = [];
  foreach ($domains as $domain) {
    $result[] = ['type' => 'domain', 'hostname' => $domain->hostname];
  }
  foreach ($aliases as $alias) {
    $result[]= [
      'type' => 'alias',
      'hostname' => $alias->domain_alias,
      'alias_of' => $alias->use_hostname
    ];
  }

  echo json_encode($result);
});

?>
