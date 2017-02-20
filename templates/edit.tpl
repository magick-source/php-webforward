<div id="edit">
	<form name="editfwd" method="post" action="./">
		<input type="hidden" name="id" value="{$forward.id}" />
		<table>
			<tr><th>Hostname:</th><td><input name="hostname" value="{$forward.hostname}"></td></tr>
			<tr><th>Forward:</th><td><input name="forward" value="{$forward.forward}"></td></tr>
			<tr><th>Active:</th><td>
				<select name="active">
					<option value="0" {if $forward.active == ""}selected="selected"{/if}>No</option>
					<option value="1" {if $forward.active == "1"}selected="selected"{/if}>Yes</option>
				</select>
			</td></tr>
			<tr><th colspan="2"><input type="submit" Value="Save" /></th></tr>
		</table>
	</form>
</div>
