<script id="edit_motion-template" type="text/x-handlebars-template">
    <div class="row col-sm-12">
    	<div class="row">
	    	<h2 class=""><span class="glyphicon glyphicon-cog"></span> Motion Server Settings</h2>
	 		<hr class="col-sm-8" style="margin-top:0px;" />
    	</div>
		<form role="form" class="form-horizontal" id="edit_motion_form">

			<input type="hidden" id="edit_motion_id" name="id" value="{{id}}" />
			<div class="form-group motion-name-field">
				<label for="edit_motion_name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="edit_motion_name" name="name" placeholder="Enter Motion Name" value="{{name}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group motion-server-field">
				<label for="edit_motion_ip" class="col-sm-2 control-label">Server/IP</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="edit_motion_ip" name="server" placeholder="0.0.0.0" value="{{server}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group motion-port-field">
				<label for="edit_motion_port" class="col-sm-2 control-label">Port Number</label>
				<div class="col-sm-5">
					<input type="number" class="form-control" id="edit_motion_port" name="port" placeholder="Server Port" value="{{port}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label for="edit_motion_username" class="col-sm-2 control-label">Username</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="edit_motion_username" name="username" placeholder="Username" value="{{username}}">
				</div>
			</div>
			<div class="form-group">
				<label for="edit_motion_password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" id="edit_motion_password" name="password" placeholder="Password" value="{{password}}">
				</div>
			</div>


			<div class="form-group" style="display:none" id="configuration_test_output">
				<div class="alert alert-success col-sm-offset-2 col-sm-5">
					<div class="row">
						<div class="col-sm-3">
							<img class="img-thumbnail" id="config_test_image" onError="App.configurationError({})" onLoad="App.configurationSuccess()" />
						</div>
						<div class="col-sm-9">
							<strong>Test successfull</strong>
							<p>Your configuration settings are correct and the motion was contacted successfully.</p>
						</div>
					</div>
				</div>
				<div class="alert alert-danger col-sm-offset-2 col-sm-5">
					<div class="row">
						<div class="col-sm-12">
							<strong>Test Failed</strong>
							<p>Your motion could not be found.  Please ensure that all settings are correct.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-5">
					<hr />
					<div class="pull-right">
						<a class="btn btn-success" id="test_config_btn">Test Settings</a>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
					<a href="." class="btn btn-default">Cancel</a>
				</div>
			</div>

		</form>
	</div>
</script>