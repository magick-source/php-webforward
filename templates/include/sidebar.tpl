<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <a class="sidebar-brand d-flex align-items-center
  justify-content-center accordion" id="accordionSidebar">
    <div class="sidebar-brand-icon">
      <i class="fas fa-link"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Web Forward</div>
  </a>
  <hr class="sidebar-divider my-0">

  {foreach $menu_items as $item}
  <li class="nav-item{if $item.id eq $menu_active} active{/if}" id='menuitem-{$item.id}'>
    <a class="nav-link" href="/admin/{$item.url}">
      <i class="fas fa-{$item.icon}"></i>
      <span>{$item.title}</span>
    </a>
  </li>
  {/foreach}

</ul>
