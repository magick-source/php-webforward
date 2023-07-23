<?php

include_once('include.php');
include_once('app/model/domain.php');
include_once('app/model/alias.php');

function check_domain(string $domain) {
  $dom_rec = Domain::find_one(array('hostname'=>$domain));
  if (!$dom_rec) {
    NOTIFY_WARNING("Domain {$domain} is not configured");
  }
}

get('/aliases/', function() { redirect('/admin/domains/');});

get('/aliases/$domain', function(string $domain){
  $page = get_page();
  check_domain($domain);
  $domains = Domain::get_list(-1);
  $aliases = Alias::get_list($domain, $page);
  $pages = Alias::get_list_pages($domain);

  build_page(
    'aliases/list.tpl',
    array(
      'domain'        => $domain,
      'domains'       => $domains,
      'aliases_list'  => $aliases,
      'pagetitle'     => "Aliases for '{$domain}'",
      'menu_active'   => 'aliases',
      'current_page'  => $page,
      'pagecount'     => $pages,
      'pageroot'      => "/admin/aliases/{$domain}",
    )
  );
});

function edit_alias_form($alias, $domain) {
    build_page('aliases/edit.tpl', array(
      'alias'       => $alias,
      'domain'      => $domain,
      'pagetitle'   => 'Edit Alias',
      'menu_active' => 'aliases'
    ));
}

post('/aliases/$domain', function($domain) {
  $alias_id = $_POST['alias_id'];
  $domain_alias = $_POST['domain_alias'];
  $use_hostname = $_POST['use_hostname'];
  $active       = $_POST['active'] ? 1 : 0;

  $errors = 0;
  if (!$use_hostname) {
    $errors++;
    NOTIFY_ERROR("the 'Use Hostname' field is required");
  } else {
    $dom = Domain::find_one(["hostname" => $use_hostname]);
    if (!$dom) {
      $errors++;
      NOTIFY_ERROR("The domain defined in 'Use Hostname' is not configured");
    }
  }

  if (!$domain_alias) {
    $errors++;
    NOTIFY_ERROR("The field 'Domain Alias' is required");
  } else {
    $dom = Domain::find_one(["hostname" => $domain_alias]);
    if ($dom) {
      $errors++;
      NOTIFY_ERROR("The hostname '{$domain_alias}' is configured as a domain");
    }
    $alias = Alias::find_one(["domain_alias" => $domain_alias]);
    if ($alias and $alias->id !== $alias_id) {
      $errors++;
      NOTIFY_ERROR("The hostname '{$domain_alias}' is already configured as an alias");
    }
  }

  $data = array(
    "domain_alias"  => $domain_alias,
    "use_hostname"  => $use_hostname,
    "flags"         => $active,
  );
  if ($errors) {
    $alias = new Alias(array_merge(array(
      "id"      => $alias_id,
      "active"  => $active,
    ), $data));
    edit_alias_form($alias, $domain);
    return;
  }

  if ($alias_id) {
    $alias = Alias::get($alias_id);
    if (!$alias)  {
      NOTIFY_ERROR("Alias not found");
      redirect("/admin/aliases/{$domain}");
    }

    $alias->update($data);
    NOTIFY_SUCCESS('Alias was successfully updated!');
  } else {
    Alias::insert($data);
    NOTIFY_SUCCESS("Alias was successfully created!");
  }

  redirect("/admin/aliases/{$domain}");
});

get('/aliases/$domain/new', function (string $domain){
  $alias = new Alias(array(
    'id'            => 0,
    'use_hostname'  => $domain,
    'domain_alias'  => '',
    'flags'         => 0,
  ));
  edit_alias_form($alias, $domain);
});

get('/aliases/$domain/edit/$id', function($domain, $id){
  $alias = Alias::get($id);
  if ($alias and $alias->use_hostname==$domain) {
    edit_alias_form($alias, $domain);
  } elseif ($alias) {
    NOTIFY_ERROR("This aliases use_hostname value ({$alias->use_hostname})  does not match");
    redirect("/admin/domains/");
  } else {
    NOTIFY_ERROR("Alias not found!");
    redirect("/admin/aliases/{$domain}");
  }
});

get('/aliases/$domain/delete/$id', function($domain,$id){
  $alias = Alias::get($id);
  if ($alias and $alias->use_hostname==$domain) {
    $alias->delete();
    NOTIFY_SUCCESS("Alias was successfully deleted!");
    redirect("/admin/aliases/{$domain}");
  } elseif ($alias) {
    NOTIFY_ERROR("This aliases use_hostname value ({$alias->use_hostname})  does not match");
    redirect("/admin/domains/");
  } else {
    NOTIFY_ERROR("Alias not found!");
    redirect("/admin/aliases/{$domain}");
  }
});

?>
