{include file="include/title.tpl" title="Domains"}

<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex justify-content-end">
		<a class="btn btn-primary" href="/admin/domains/new">New Domain</a>
	</div>
  <div class="card-body">
  	<div class="table-responsive">

{include file='include/pages.tpl'}
<table class="table table-striped">
	<thead>
		<tr>
			<th>Hostname</th>
			<th>Type</th>
			<th>Root Forward</th>
			<th>Not Found Forward</th>
			<th>Aliases</th>
			<th>URLs</th>
			<th></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Hostname</th>
			<th>Type</th>
			<th>Root Forward</th>
			<th>Not Found Forward</th>
			<th>Aliases</th>
			<th>URLs</th>
			<th></th>
		</tr>
	</tfoot>
	<tbody>
{section name=i loop=$domain_list}
	<tr>
		<td><a class="class" href="/admin/domains/edit/{$domain_list[i]->id}">
			{$domain_list[i]->hostname}
		</a></td>
		<td>{$domain_list[i]->domain_type}</td>
		<td><a href="{$domain_list[i]->root_forward}">
			{$domain_list[i]->root_forward}
		</a></td>
		<td>
		{if $domain_list[i]->not_found}
			<a href="{$domain_list[i]->not_found}">
				{$domain_list[i]->not_found}
			</a>
		{else}
			-
		{/if}
		</td>
		<td><a href="/admin/aliases/{$domain_list[i]->hostname}">{$domain_list[i]->alias_count}</a></td>
		<td>
		{if $domain_list[i]->domain_type == 'url_based'}
		  <a
		  href="/admin/urls/{$domain_list[i]->hostname}">{$domain_list[i]->url_count}</a>
		{else}
			-
		{/if}
		</td>
		<td class="d-flex">
			<a class="p-1" href="/admin/domains/edit/{$domain_list[i]->id}">
				<i class="fas fa-pen"></i>
			</a>
			<a class="p-1" href="/admin/domains/delete/{$domain_list[i]->id}">
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
		<a class="btn btn-primary" href="/admin/domains/new">New Domain</a>
	</div>
</div>
