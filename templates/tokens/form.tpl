{include file="include/title.tpl" title=$pagetitle}

<div class="card">
  <form name="newtoken" method="post" action="/admin/tokens/">
    <div class="card-body">
      <div class="mb-3">
        <label for="input-name" class="form-label">Token Name</label>
        <input class="form-control" id="input-name"
          name="name" aria-describedBy="help-name"
          value="{$token->name}" />
        <div id="help-name" class="form-text">The name of the token</div>
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Save</button>
      <a href="/admin/tokens/" class="btn btn-cancel">Cancel</a>
    </div>
  </form>
</div>
