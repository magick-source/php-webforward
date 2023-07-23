{include file='include/title.tpl' title=$pagetitle}

<div class="card shadow mb-4">
  <div class="card-body">
    <p class="form-text">
      Copy the API token, as it will not be accessible later.
    </p>
    <div><code class="token">{$token}</code></div>
    <p class="form-text">
      The token can be used as a get parameter on the API calls:

      <code>
        {$api_url}domains?token={$token}
      </code>
    </p>
  </div>
  <div class="card-footer py-3 d-flex justify-content-end">
    <a class="btn btn-outline-secondary m-1" href="/admin/tokens/">&lt;Back</a>
  </div>
</div>
