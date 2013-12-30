<script id="user_settings-template" type="text/x-handlebars-template">
	<div class="col-md-offset-2 col-md-8">
		<div class="col-md-12 form-heading">
			<h1><span class="glyphicon glyphicon-user"></span> User Details</h1>
			<hr class="col-md-8" />
		</div>

		<form role="form" class="form-horizontal user-settings-form">
			<div class="form-group user-username-field">
				<label for="user_name" class="col-sm-2 control-label">Username</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="user_name" name="username" placeholder="User Name" value="{{username}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group user-password-field">
				<label for="user_password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="user_password" name="password" placeholder="Password" value="">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group user-confirm_password-field">
				<label for="confirm_password" class="col-sm-2 control-label">Confirm</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group user-date_format-field">
				<label class="col-sm-2 control-label">Date Format</label>
				<div class="col-sm-5">
					<select class="form-control date-format-select" name="date_format">
						<option value="DD/MM/YYYY">DD/MM/YYYY</option>
						<option value="YYYY-MM-DD">YYYY-MM-DD</option>
						<option value="MM/DD/YYYY">MM/DD/YYYY</option>
					</select>
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group user-time_format-field">
				<label class="col-sm-2 control-label">Time Format</label>
				<div class="col-sm-5">
					<label class="checkbox-inline">
						<input type="radio" name="time_format" class="time_24" value="HH:mm:ss">
						24 Hour
					</label>
					<label class="checkbox-inline">
						<input type="radio" name="time_format" class="time_12" value="hh:mm:ssA">
						12 Hour
					</label>
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<p class="col-md-7">
				<button type="submit" class="btn btn-primary pull-right">Save</button>
			</p>
		</form>
	</div>
</script>