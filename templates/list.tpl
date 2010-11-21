<div id="list">

<a href="./new.php">New Forward</a>
{include file=pages.tpl}
<table class="data">
	<tr><th>Hostname</th><th>Forward to</th>
		<th>Edit</th><th>Delete</th>
	</tr>
{section name=i loop=$forwardlist}
	<tr class="{cycle values="odd,even"}">
		<td><a class="class" href="./edit.php?id={$forwardlist[i].id}">
			{$forwardlist[i].hostname}
		</a></td>
		<td><a href="{$forwardlist[i].forward}">
			{$forwardlist[i].forward}
		</a></td>
		<td><a class="class" href="./edit.php?id={$forwardlist[i].id}">
			Edit
		</a></td>
		<td><a class="class" href="./?id={$forwardlist[i].id}&delete=y">
			Delete
		</a></td>
	</tr>
{/section}
</table>
{include file=pages.tpl}
<a href="./new.php">New Forward</a>

</div>
