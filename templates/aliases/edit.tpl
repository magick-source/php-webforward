{include file="include/title.tpl" title="Edit Alias"}

<div class="card">
	<form name="editalias" method="post" action="/admin/aliases/{$domain}">
		<input type="hidden" name="alias_id" value="{$alias->id}" />
		<div class="card-body">
			<div class="mb-3">
				<label for="input-hostname" class="form-label">Domain Alias</label>
				<input class="form-control" id="input-alias-domain"
				name="domain_alias" aria-describedBy="help-alias-domain"
				value="{$alias->domain_alias}" />
				<div id="help-alias-domain" class="form-text">The domain that
				copies the configuration</div>
			</div>
			<div class="mb-3">
				<label for="input-use-domain" class="form-label">Use Hostname</label>
				<input class="form-control" id="input-use-hostname"
				name="use_hostname"	aria-describedBy="help-use-hostname"
				value="{$alias->use_hostname}" readonly=1 />
				<div id="help-use-hostname" class="form-text">
					When handling a request for <b>Alias Domain</b>, use the
					configuration of <b>Use Hostname</b>, instead.
				</div>
			</div>
			<div class="mb-3">
				<label for="input-active" class="form-label">Active</label>
				<select name="active" class="form-control">
					<option value="0" {if $alias->active == ""}selected="selected"{/if}>No</option>
					<option value="1" {if $alias->active == "1"}selected="selected"{/if}>Yes</option>
				</select>
				<div id="help-active" class="form-text">
					Is the alias active?
					The domain is ignored if not active
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary">Save</button>
			<a href="/admin/aliases/{$domain}" class="btn btn-cancel">Cancel</a>
		</div>
	</form>
</div>
