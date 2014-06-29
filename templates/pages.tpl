{if $pages}
<div class="paging">
{if $current_page != 1}
	<a class="class" href="{$page_root}?page=1">&lt;&lt;First</a>
	<a class="class" href="{$page_root}?page={math equation="$current_page - 1"}">&lt;Previous</a>
{/if}
{foreach name=pages from=$pages item=page}
	{if $current_page == $page}
		{$page}
	{else}
		<a class="class" href="{$page_root}?page={$page}">{$page}</a>
	{/if}
{/foreach}
{if $current_page != $pagecount}
	<a class="class" href="{$page_root}?page={math equation="$current_page + 1"}">Next&gt;</a>
	<a class="class" href="{$page_root}?page={$pagecount}">Last&gt;&gt;</a>
{/if}
</div>
{/if}
