<?php

include_once('include.php');
include_once('app/model/token.php');

get('/tokens/', function() {
  $page = get_page();
  $tokens = Token::get_list($page);
  $pages = Token::get_list_pages();

  build_page('tokens/list.tpl', [
    'token_list'    => $tokens,
    'pagetitle'     => 'List Tokens',
    'menu_active'   => 'tokens',
    'current_page'  => $page,
    'pagecount'     => $pages,
    'pageroot'      => '/admin/tokens/',
  ]);
});

function new_token_form(Token $token) {
  build_page('tokens/form.tpl',[
    'token'       => $token,
    'pagetitle'   => 'New Token',
    'menu_active' => 'tokens',
  ]);
}

get('/tokens/new', function () {
  $token = new Token([
    'id'      => 0,
    'user_id' => 0,
    'name'    => '',
    'flags'   => 0,
  ]);
  new_token_form($token);
});

get('/tokens/expire/$token_id', function ($token_id) {
  $token = Token::get($token_id);
  if ($token) {
    $token->expire();
    NOTIFY_SUCCESS("Token successfully expired");
  } else {
    NOTIFY_ERROR("Token not found!");
  }

  redirect("/admin/tokens/");
});

post('/tokens/', function(){
  $token_name = $_POST['name'];

  $errors=0;
  if (!$token_name) {
    NOTIFY_ERROR("The name of the token is required");
    $errors++;
  } elseif (!preg_match('/\A\w[\w-]{1,18}\w\z/', $token_name )) {
    NOTIFY_ERROR('The token name must be 3 to 20 alphanumeric chars');
    $errors++;
  } else {
    $old = Token::find_one(['name' => $token_name]);
    if ($old) {
      $errors++;
      NOTIFY_ERROR("There is already a token named {$token_name}");
    }
  }

  if ($errors) {
    new_token_form(new Token([
      'id'    => 0,
      'name'  => $token_name,
      'flags' => 0,
    ]));
    return;
  }

  $token_details = Token::generate_token($token_name);
  build_page('tokens/details.tpl',[
    'pagetitle'   => 'Token Details',
    'menu_active' => 'tokens',
    'api_url'     => '/api/v1/',
    'token_name'  => $token_details['name'],
    'token'       => $token_details['token'],
  ]);

});

?>
