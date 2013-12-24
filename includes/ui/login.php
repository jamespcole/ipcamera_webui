<script id="login-template" type="text/x-handlebars-template">
	<form class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3" role="form" id="login_form">
		<h2 class="form-signin-heading">Please sign in</h2>
		<span class="help-block validation-message" style="display:none;"></span>
		<div class="form-group">
			<input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Password" required>
		</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	</form>
</script>