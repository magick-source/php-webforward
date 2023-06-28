<div id="list">

<a href="./new-domain.php">New Domain</a>
{include file='pages.tpl'}
<table class="data">
	<tr>
		<th>Hostname</th>
		<th>Type</th>
		<th>Root Forward</th>
		<th>Not Found Forward</th>
		<th>Aliases</th>
		<th>URLs</th>
		<th>Edit</th><th>Delete</th>
	</tr>
{section name=i loop=$forwardlist}
	<tr class="{cycle values="odd,even"}">
		<td><a class="class" href="./edit.php?id={$forwardlist[i].id}">
			{$forwardlist[i].hostname}
		</a></td>
		<td>{$forwardlist[i].domain_type}</td>
		<td><a href="{$forwardlist[i].root_forward}">
			{$forwardlist[i].root_forward}
		</a></td>
		<td>
		{if $forwardlist[i].not_found}
			<a href="{$forwardlist[i].not_found}">
				{$forwardlist[i].not_found}
			</a>
		{else}
			-
		{/if}
		</td>
		<td><a href="./aliases.php?domain={$forwardlist[i].hostname}">{$forwardlist[i].aliases_count}</a></td>
		<td>
		{if $forwardlist[i].domain_type == 'url_based'}
		  <a
		  href="./?domain={$forwardlist[i].hostname}">{$forwardlist[i].url_count}</a>
		{else}
			-
		{/if}
		</td>
		<td><a class="class" href="./edit-domain.php?id={$forwardlist[i].id}">
			Edit
		</a></td>
		<td><a class="class" href="./?type=domain&id={$forwardlist[i].id}&delete=y">
			Delete
		</a></td>
	</tr>
{/section}
</table>
{include file='pages.tpl'}
<a href="./new-domain.php">New Domain</a>

</div>
