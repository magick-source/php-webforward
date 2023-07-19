{include file="include/title.tpl" title="Edit Url Forward"}

<div class="card">
	<form name="editurl" method="post" action="/admin/urls/{$domain}">
		<input type="hidden" name="url_id" value="{$url->id}" />
		<div class="card-body">
			<div class="mb-3">
				<label for="input-hostname" class="form-label">Hostname</label>
				<input class="form-control" id="input-hostname"
				name="hostname" aria-describedBy="help-hostname"
				value="{$url->hostname}" readonly=1/>
				<div id="help-hostname" class="form-text">The hostname of the
				original URL</div>
			</div>
			<div class="mb-3">
				<label for="input-url" class="form-label">Url to Forward</label>
				<input class="form-control" id="input-url"
				name="url"	aria-describedBy="help-url"
				value="{$url->url}" />
				<div id="help-url" class="form-text">
          The path of the original URL to redirect
				</div>
			</div>
      <div class="mb-3">
        <label for="input-forward" class="form-label">Forward
        to</label>
        <input class="form-control" id="input-forward"
          name="forward" aria-describedBy="help-forward"
          value="{$url->forward}" />
        <div id="help-forward" class="form-text">
          The URL the visitor will be redirected to.
        </div>
      </div>
			<div class="mb-3">
				<label for="input-active" class="form-label">Active</label>
				<select name="active" class="form-control">
					<option value="0" {if $url->active == ""}selected="selected"{/if}>No</option>
					<option value="1" {if $url->active == "1"}selected="selected"{/if}>Yes</option>
				</select>
				<div id="help-active" class="form-text">
					Is the url active?
					The url is ignored if not active
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary">Save</button>
			<a href="/admin/urls/{$domain}" class="btn btn-cancel">Cancel</a>
		</div>
	</form>
</div>
