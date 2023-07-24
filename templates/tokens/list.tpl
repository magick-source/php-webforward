{include file="include/title.tpl" title=$pagetitle}

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-end">
    <a class="btn btn-outline-secondary m-1"
    href="/admin/">&lt;Back</a>
    <a class="btn btn-primary m-1" href="{$pageroot}new">New Token</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">

{include file='include/pages.tpl'}
<table class='table table-striped'>
  <thead>
    <tr>
      <th>Name</th>
      <th>User</th>
      <th>Created At</th>
      <th>Expires At</th>
      <th>Cancelled At</th>
      <th></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>Name</th>
      <th>User</th>
      <th>Created At</th>
      <th>Expires At</th>
      <th>Cancelled At</th>
      <th></th>
    </tr>
  </tfoot>
  <tbody>
{section name=i loop=$token_list}
    <tr>
      <td>{$token_list[i]->name}</td>
      <td>{$token_list[i]->username}</td>
      <td>{$token_list[i]->created_at}</td>
      <td>{$token_list[i]->expires_at}</td>
      <td>{$token_list[i]->cancelled_at}</td>
      <td>
        {if $token_list[i]->active }
        <a class="p-1" href="{$pageroot}expire/{$token_list[i]->id}">
          <i class="fas fa-ban"></i>
        </a>
        {/if}
      </td>
    </tr>
{/section}
  </tbody>
</table>
{include file='include/pages.tpl'}

    </div>
  </div>
  <div class="card-footer py-3 d-flex justify-content-end">
    <a class="btn btn-outline-secondary m-1"
    href="/admin/">&lt;Back</a>
    <a class="btn btn-primary m-1" href="{$pageroot}new">New Token</a>
  </div>
</div>
