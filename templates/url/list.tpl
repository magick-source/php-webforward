<div id="list">

<div class="list-links">
<a href="./new-url.php?hostname={$hostname}">New URL</a>
<a href="./">Back to domain list</a>
</div>
{include file='pages.tpl'}
<table class="data">
	<tr><th>Hostname</th><th>URL</th><th>Forward to</th>
		<th>Edit</th><th>Delete</th>
	</tr>
{section name=i loop=$forwardlist}
	<tr class="{cycle values="odd,even"}">
		<td><a class="class" href="./edit.php?id={$forwardlist[i].id}">
			{$forwardlist[i].hostname}
		</a></td>
		<td><a href="//{$forwardlist[i].hostname}{$forwardlist[i].url}">
			{$forwardlist[i].url}
		</a></td>
		<td><a href="{$forwardlist[i].forward}">
			{$forwardlist[i].forward}
		</a></td>
		<td><a class="class" href="./edit-url.php?id={$forwardlist[i].id}">
			Edit
		</a></td>
		<td><a class="class" href="./?id={$forwardlist[i].id}&delete=y">
			Delete
		</a></td>
	</tr>
{/section}
</table>
{include file='pages.tpl'}
<div class="list-links">
<a href="./new-url.php?hostname={$hostname}">New URL</a>
<a href="./">Back to domain list</a>
</div>
</div>
