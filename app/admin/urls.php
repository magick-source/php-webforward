<?php

include_once('include.php');
include_once('app/model/domain.php');
include_once('app/model/url.php');

function check_domain_for_urls(string $domain) {
  $dom_rec = Domain::find_one(['hostname' => $domain]);
  $is_good = true;
  if (!$dom_rec) {
    NOTIFY_ERROR("Domain {$domain} is not configured");
    $is_good = false;
  } elseif ($dom_rec->domain_type !== 'url_based') {
    NOTIFY_ERROR("Domain is configured with '{$dom_rec->domain_type}', not 'url_based'");
    $is_good = false;
  }
  if (!$is_good) {
    redirect("/admin/domains");
  }
  return $is_good;
}

get('/urls/', function(){ redirect('/admin/domains/'); });

get('/urls/$domain', function($domain){
  $page = get_page();
  if (!check_domain_for_urls($domain)) {
    return;
  }
  $urls = Url::get_list($domain, $page);
  $pages = Url::get_list_pages($domain);

  build_page(
    'urls/list.tpl',
    array(
      'domain'        => $domain,
      'url_list'      => $urls,
      'pagetitle'     => "Urls for '{$domain}'",
      'menu_active'   => 'urls',
      'current_page'  => $page,
      'pagecount'     => $pages,
      'pageroot'      => "/admin/urls/{$domain}",
    )
  );
});

function edit_url_form($url, $domain) {
  build_page('urls/edit.tpl', array(
    'domain'      => $domain,
    'url'         => $url,
    'pagetitle'   => 'Edit URL Forward',
    'menu_active' => 'urls',
  ));
}

post('/urls/$domain', function ($domain){
  $url_id = $_POST['url_id'];
  $hostname = $_POST['hostname'];
  $url      = $_POST['url'];
  $forward  = $_POST['forward'];
  $active   = $_POST['active'] ? 1 : 0;

  $data = [
    'hostname'  => $hostname,
    'url'       => $url,
    'forward'   => $forward,
    'flags'     => $active,
  ];
  $errors = 0;
  if (!$hostname) {
    $errors++;
    NOTIFY_ERROR("The 'hostname' field is required.");
  } else {
    $dom = Domain::find_one(["hostname" => $hostname]);
    if (!$dom) {
      $errors++;
      NOTIFY_ERROR("The domain defined in 'hostname' is not configured");
    } elseif ($dom->domain_type !== 'url_based') {
      $errors++;
      NOTIFY_ERROR("The domain '{$hostname} is not set as 'url_based'");
    }
  }

  if ($errors == 0) {
    if ($url == '' or $url == '/') {
      $errors++;
      NOTIFY_ERROR("Use the root_forward of the domain to redirect the '/' url");
    } else {
      $ourl = Url::find_one(["url" => $url, "hostname" => $hostname]);
      if ($ourl and $ourl->id != $url_id) {
        $errors++;
        NOTIFY_ERROR("The URL {$hostname} {$url} is already defined");
      }
    }

    if (!$forward) {
      $errors++;
      NOTIFY_ERROR("The field 'forward' is required");
    }
  }

  if ($errors) {
    $url = new Url(array_merge([
      'url_id'  => $url_id,
      'active'  => $active,
    ],$data));
    edit_url_form($url, $domain);
    return;
  }

  if ($url_id) {
    $url = Url::get($url_id);
    if (!$url) {
      NOTIFY_ERROR('Url not found');
      redirect("/admin/urls/{$domain}");
    }
    $url->update($data);
    NOTIFY_SUCCESS("Url updated successfully!");
  } else {
    Url::insert($data);
    NOTIFY_SUCCESS("Url was successfully created!");
  }

  redirect("/admin/urls/{$domain}");
});

get('/urls/$domain/new', function($domain){
    $url = new Url([
      'id'        => 0,
      'hostname'  => $domain,
      'url'       => '',
      'forward'   => '',
      'flags'     => 0,
    ]);
    edit_url_form($url, $domain);
});

get('/urls/$domain/edit/$id', function ($domain, $id) {
  $url = Url::get($id);
  if ($url and $url->hostname === $domain) {
    edit_url_form($url, $domain);
  } elseif($url) {
    NOTIFY_ERROR("This url hostname ({$url->hostname}) does not match");
    redirect('/admin/domains/');
  } else {
    NOTIFY_ERROR("Url not found");
    redirect("/admin/urls/{$domain}");
  }
});

get('/urls/$domain/delete/$id', function ($domain, $id) {
  $url = Url::get($id);
  if ($url and $url->hostname === $domain) {
    $url->delete();
    NOTIFY_SUCCESS('Url was successfully deleted!');
    redirect("/admin/urls/{$domain}");
  } elseif ($url) {
    NOTIFY_ERROR("This url hostname does not match");
    redirect('/admin/domains');
  } else {
    NOTIFY_ERROR('Url not found!');
    redirect("/admin/urls/{$domain}");
  }
});

?>
