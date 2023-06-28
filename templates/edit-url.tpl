<div id="edit">
  <form name="editurl" method="post" action="./?domain={$url.hostname}">
    <input type="hidden" name="type" value="url" />
    <input type="hidden" name="url_id" value="{$url.id}" />
    <input type="hidden" name="hostname" value="{$url.hostname}" />
    <table>
      <tr><th>URL</th><td><input name="url" value="{$url.url}"
      /></td></tr>
      <tr><th>Forward to:</th><td><input name="forward"
      value="{$url.forward}" /></td></tr>
			<tr><th>Active:</th><td>
				<select name="active">
					<option value="0" {if $url.active == ""}selected="selected"{/if}>No</option>
					<option value="1" {if $url.active == "1"}selected="selected"{/if}>Yes</option>
				</select>
			</td></tr>
			<tr><th colspan="2"><input type="submit" Value="Save" /></th></tr>
    </table>
  </form>
</div>
