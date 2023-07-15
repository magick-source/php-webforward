
<div class="card o-hidden border-0 shadow-lg my-5">
	<div class="card-header p-2">
		<h3 class="m-3">Welcome to WebForward</h3>
	</div>
	<div class="card-body">
		<form class="user" method="post" name="login">
			<div class="form-group">
				<input class="form-control"
					id="username" name="username"
					value="{$login_username}"
					placeholder="Username..." />
			</div>
			<div class="form-group">
				<input type="password"
					class="form-control"
					id="password" name="password"
					value="{$login_password}"
					placeholder="Password..." />
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
</div>

