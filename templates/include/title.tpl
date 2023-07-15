<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">{$title}</h1>
  {if isset($actions) && $actions}
    {foreach $actions as $action}
      <a href="{$action.url}" class="d-none d-sm-inline-block btn
      btn-sm btn-primary shadow-sm">
        <i class="fas fa-{$action.icon} fa-sm text-white-50"></i>
        {$action.title}
      </a>
    {/foreach}
  {/if}
</div>
