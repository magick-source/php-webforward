{if isset($pagecount)}
<div class="btn-toolbar justify-content-center m-3" role="toolbar">
{if $current_page > 1}
	<div class="btn-group me-2" role="group">
		<a class="btn btn-outline-primary" href="{$pageroot}?page=1">&lt;&lt;First</a>
		<a class="btn btn-outline-primary" href="{$pageroot}?page={math equation="$current_page - 1"}">&lt;Previous</a>
	</div>
{/if}

	<div class="btn-group me-2" role="group">
{for $page=1 to $pagecount}
	{if $current_page == $page}
		<a class="btn btn-primary" href="{$pageroot}?page={$page}">{$page}</a>
	{else}
		<a class="btn btn-outline-primary" href="{$pageroot}?page={$page}">{$page}</a>
	{/if}
{/for}
	</div>

{if $current_page < $pagecount}
	<div class="btn-group me-2" role="group">
		<a class="btn btn-outline-primary" href="{$pageroot}?page={math equation="$current_page + 1"}">Next&gt;</a>
		<a class="btn btn-outline-primary" href="{$pageroot}?page={$pagecount}">Last&gt;&gt;</a>
	</div>
{/if}

</div>
{/if}
