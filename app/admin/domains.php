<?php

include_once('include.php');
include_once('app/model/domain.php');

get('/domains/', function () {
  $page     = get_page();
  $domains  = Domain::get_list($page);
  $pages    = Domain::get_list_pages();

  build_page(
    'domain/list.tpl',
    array(
      'domain_list'   => $domains,
      'pagetitle'     => 'List Domains',
      'menu_active'   => 'domains',
      'current_page'  => $page,
      'pagecount'     => $pages,
      'pageroot'      => '/admin/domains/',
    )
  );
});

function edit_domain_form(Domain $domain) {
    build_page('domain/edit.tpl',array(
      'domain'      => $domain,
      'pagetitle'   => 'Edit Domain',
      'menu_active' => 'domains',
    ));
}

get('/domains/new', function(){
  $domain = new Domain(array(
    'id'            => 0,
    'hostname'      => '',
    'domain_type'   => '',
    'root_forward'  => '',
    'not_found'     => '',
    'flags'         => 0,
  ));
  edit_domain_form($domain);
});

get('/domains/delete/$domain_id', function($domain_id){
  $domain = Domain::get($domain_id);
  if ($domain) {
    $domain->delete();
    NOTIFY_SUCCESS("Domain was successfully deleted");
  } else {
    NOTIFY_ERROR("Domain is {$domain_id} not found!");
  }
  redirect('/admin/domains/');
});

get('/domains/edit/$domain_id', function($domain_id) {
  $domain = Domain::get($domain_id);
  if ($domain) {
    edit_domain_form($domain);
  } else {
    NOTIFY_ERROR("Domain with id {$domain_id} not found!");
    redirect('/admin/domains/');
  }
});

post('/domains/', function() {
  $domain_id = $_POST['domain_id'];

	$host 		 = $_POST['hostname'];
	$type 		 = $_POST['domain_type'];
	$rfwd			 = $_POST['root_forward'];
	$not_found = $_POST['not_found'];
	$active 	 = $_POST['active'] ? 1 : 0;

	$errors = 0;
  if (!$host) {
    $errors++;
    NOTIFY_ERROR('Host is required');
  }
  if ($type != 'url_based' && !$rfwd) {
    $errors++;
    NOTIFY_ERROR(
      'Unless the type is "URL Based", the "Root Forward" field is required'
    );
  }
  $data = array(
      "hostname"      => $host,
      "domain_type"   => $type,
      "root_forward"  => $rfwd,
      "not_found"     => $not_found,
      "flags"         => $active,
  );
  if ($errors) {
    $domain = new Domain(array_merge(array(
      "id"            => $domain_id,
      "active"        => $active,
    ), $data));
    edit_domain_form($domain);
    return;
  }

  if ($domain_id) {
    $domain = Domain::get($domain_id);
    if (!$domain) {
      NOTIFY_ERROR('Domain not found');
      redirect('/admin/domains/');
    }

    $domain->update($data);
    NOTIFY_SUCCESS('Domain was successfully updated!');
  } else {

    # New domain!
    Domain::insert($data);
    NOTIFY_SUCCESS('Domain was successfully created!');
  }
  redirect('/admin/domains/');
});

?>
