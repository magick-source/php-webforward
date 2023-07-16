{include file="include/title.tpl" title=$pagetitle}

<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex justify-content-end">
		<a class="btn btn-outline-secondary m-1" href="/admin/domains/">&lt; Back</a>
		<a class="btn btn-primary m-1" href="{$pageroot}/new">New Alias</a>
	</div>
  <div class="card-body">
  	<div class="table-responsive">

{include file='include/pages.tpl'}
<table class="table table-striped">
	<thead>
		<tr>
			<th>Alias Domain</th>
			<th>Use Domain</th>
			<th>Is Active</th>
			<th></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Alias Domain</th>
			<th>Use Domain</th>
			<th>Is Active</th>
			<th></th>
		</tr>
	</tfoot>
	<tbody>
{section name=i loop=$aliases_list}
	<tr>
		<td><a class="class" href="{$pageroot}/edit/{$aliases_list[i]->id}">
			{$aliases_list[i]->domain_alias}
		</a></td>
		</td>
		<td>
			{$aliases_list[i]->use_hostname}
		</td>
		<td>
			{if ($aliases_list[i]->active)}true{else}false{/if}
		</td>
		<td class="d-flex">
			<a class="p-1" href="{$pageroot}/edit/{$aliases_list[i]->id}">
				<i class="fas fa-pen"></i>
			</a>
			<a class="p-1" href="{$pageroot}/delete/{$aliases_list[i]->id}">
				<i class="fas fa-trash"></i>
			</a>
		</td>
	</tr>
{/section}
	</tbody>
</table>
{include file='include/pages.tpl'}
		</div>
	</div>
	<div class="card-footer py-3 d-flex justify-content-end">
		<a class="btn btn-outline-secondary m-1" href="/admin/domains/">&lt; Back</a>
		<a class="btn btn-primary m-1" href="{$pageroot}/new">New Alias</a>
	</div>
</div>
