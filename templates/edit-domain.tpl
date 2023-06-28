<div id="edit">
	<form name="editfwd" method="post" action="./">
		<input type="hidden" name="type" value="domain" />
		<input type="hidden" name="domain_id" value="{$forward.id}" />
		<table>
			<tr><th>Hostname:</th><td><input name="hostname"
			value="{$forward.hostname}"></td></tr>
			<tr><th>Domain Type:</th><td>
				<select name="domain_type">
					<option value="ignore_uri" {if $forward.domain_type == "ignore_uri"}selected="selected"{/if}>Ignore URI</option>
					<option value="forward" {if $forward.domain_type == "forward"}selected="selected"{/if}>Forward URI</option>
					<option value="rule_based" {if $forward.domain_type ==
					"rule_based"}selected="selected"{/if}>Rule Based</option>
					<option value="url_based" {if $forward.domain_type ==
					"url_based"}selected="selected"{/if}>URL Based</option>
				</select>
			</td></tr>
			<tr><th>Root Forward:</th><td><input name="root_forward" value="{$forward.root_forward}"></td></tr>
			<tr><th>404 Forward:</th><td><input name="not_found" value="{$forward.not_found}"></td></tr>
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
