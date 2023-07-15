{include file="include/title.tpl" title="Edit Domain"}

<div class="card">
	<form name="editdomain" method="post" action="/admin/domains">
		<input type="hidden" name="domain_id" value="{$domain->id}" />
		<div class="card-body">
			<div class="mb-3">
				<label for="input-hostname" class="form-label">Hostname</label>
				<input class="form-control" id="input-hostname" name="hostname"
				aria-describedBy="help-hostname" value="{$domain->hostname}" />
				<div id="help-hostname" class="form-text">The domain to
				redirect</div>
			</div>
			<div class="mb-3">
				<label for="input-domain-type" class="form-label">Domain Type</label>
				<select class="form-control" name="domain_type"
				id="input-domain-type" aria-describedBy="help-domain-type">
					<option value="ignore_uri" {if $domain->domain_type == "ignore_uri"}selected="selected"{/if}>Ignore URI</option>
					<option value="forward" {if $domain->domain_type == "forward"}selected="selected"{/if}>Forward URI</option>
					<option value="rule_based" {if $domain->domain_type ==
					"rule_based"}selected="selected"{/if}>Rule Based</option>
					<option value="url_based" {if $domain->domain_type ==
					"url_based"}selected="selected"{/if}>URI Based</option>
				</select>
				<div id="help-domain-type" class="form-text">
					<p>The Domain Type defines how the domain is handled:</p>
					<ul>
						<li><strong>Ignore URI</strong>: Any URI path the original
						URL contains is ignored and the <i>Root URL</i> is always
						used by itself.</li>
						<li><strong>Forward</strong>: the resulting URL will be
						similar to the original URL, with the hostname part
						replaced</li>
						<li><strong>Rule Based</strong>: the URL is split in parts
						and those parts are used to build the new URL using the
						<i>Not Found URL</i></li>
						<li><strong>URI Based</strong>: the URI part of the URL is
						used to find a matching URI, and the redirect defined for
						that URI is used.</li>
					</ul>
				</div>
			</div>
			<div class="mb-3">
				<label for="input-root-forward" class="form-label">Root Forward</label>
				<input class="form-control" id="input-root-forward" name="root_forward"	aria-describedBy="help-root-forward" value="{$domain->root_forward}" />
				<div id="help-root-forward" class="form-text">The URL is used
				when the domain type is <i>ignore_uri</i>, is the base for
				the forwarded URL when the type is <i>forward</i>, and is used
				when the original URL doesn't have an URI path</div>
			</div>
			<div class="mb-3">
				<label for="input-not-found" class="form-label">Not Found</label>
				<input class="form-control" id="input-not-found" name="not_found"	aria-describedBy="help-not-found" value="{$domain->not_found}" />
				<div id="help-not-found" class="form-text">
					<p>Used when the original URL have a URI part the domain type
					is <i>Rule Based</i> or it is <i>URI Based</i> and the URI is not defined.</p>
					<p>The <b>Not Found</b> field may include the following
					variables:</p>
					<ul>
						<li><code>%domain%</code>: the original domain.</li>
						<li><code>%domain_encoded%</code>: the original domain.</li>
						<li><code>%uri_path%</code>: the original uri path.</li>
						<li><code>%uri_path_encoded%</code>: the original uri
						path, URL encoded to be used as a query_string value.</li>
						query string, url encoded to be used as a query_string value.</li>
					</ul>
					<p>The <i>root forward</i> is used if this field is not set.</p>
				</div>
			</div>
			<div class="mb-3">
				<label for="input-active" class="form-label">Active</label>
				<select name="active" class="form-control">
					<option value="0" {if $domain->active == ""}selected="selected"{/if}>No</option>
					<option value="1" {if $domain->active == "1"}selected="selected"{/if}>Yes</option>
				</select>
				<div id="help-active" class="form-text">Is the domain active?
				The domain is ignored if not active. Any URI rules and aliases
				are also ignored.</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary">Save</button>
			<a href="/admin/domains" class="btn btn-cancel">Cancel</a>
		</div>
	</form>
</div>
