<script id="edit_motion-template" type="text/x-handlebars-template">
    <div class="col-md-offset-2 col-md-10">
    	<div class="col-md-12 form-heading">
	    	<h1 class=""><span class="glyphicon glyphicon-cog"></span> Motion Server Settings</h1>
	 		<hr class="col-md-8" />
    	</div>
		<div class="row col-md-offset-1 col-sm-offset-1">
			<p class="col-md-10 col-sm-11">
				This page is for configuring a connection to a motion server.  <br />If you do not have motion installed yet <a href="" data-toggle="modal" data-target="#motion_instructions">click here</a> for instructions on setting it up.
			</p>
    	</div>
		<form role="form" class="form-horizontal edit_motion_form" id="edit_motion_form">

			<input type="hidden" id="edit_motion_id" name="id" value="{{id}}" />
			<input type="hidden" id="edit_motion_url" name="url" value="{{url}}" />
			<div class="form-group motion-name-field">
				<label for="edit_motion_name" class="col-md-2 col-sm-2 control-label">Name</label>
				<div class="col-md-5 col-sm-6">
					<input type="text" class="form-control" id="edit_motion_name" name="name" placeholder="Enter Motion Name" value="{{name}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2">Protocol</label>
				<div class="col-md-5 col-sm-6">
					<select class="form-control" name="protocol" id="edit_motion_protocol">
						<option value="http">HTTP</option>
						<option value="https">HTTPS</option>
					</select>
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
			</div>
			<div class="form-group motion-server-field">
				<label for="edit_motion_ip" class="control-label col-md-2 col-sm-2">Server/IP</label>
				<div class="col-md-5 col-sm-6">
					<input type="text" class="form-control" id="edit_motion_ip" name="server" placeholder="0.0.0.0" value="{{server}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
			</div>
			<div class="form-group motion-port-field">
				<label for="edit_motion_port" class="control-label col-md-2 col-sm-2">Port Number</label>
				<div class="col-md-5 col-sm-6">
					<input type="number" class="form-control" id="edit_motion_port" name="port" placeholder="Server Port" value="{{port}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label for="edit_motion_username" class="control-label col-md-2 col-sm-2">Username</label>
				<div class="col-md-5 col-sm-6">
					<input type="text" class="form-control" id="edit_motion_username" name="username" placeholder="Username" value="{{username}}">
				</div>
			</div>
			<div class="form-group">
				<label for="edit_motion_password" class="control-label col-md-2 col-sm-2">Password</label>
				<div class="col-md-5 col-sm-6">
					<input type="password" class="form-control" id="edit_motion_password" name="password" placeholder="Password" value="{{password}}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" id="edit_motion_local_server" name="local_server"> The motion server is running on the same machine as this app
						</label>
					</div>
				</div>
			</div>
			<div class="form-group motion-config_file-field">
				<label for="edit_motion_config_file" class="control-label col-md-2 col-sm-2">Config Path</label>
				<div class="col-md-5 col-sm-6">
					<input type="text" class="form-control" id="edit_motion_config_file" name="config_file" placeholder="Config File" value="{{config_file}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
			</div>


			<div class="form-group configuration_test_output" style="display:none">
				<div class="alert alert-success col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<strong>Test successfull</strong>
							<p>Your configuration settings are correct and the motion server was contacted successfully.</p>
						</div>
					</div>
				</div>
				<div class="alert alert-danger col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-6">
					<div class="row">
						<div class="col-sm-12">
							<strong>Test Failed</strong>
							<p class="error-message">Your motion could not be found.  Please ensure that all settings are correct.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-6">
					<hr />
					<div class="pull-right">
						<a class="btn btn-success test_config_btn">Test Settings</a>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
					<a href="." class="btn btn-default">Cancel</a>
				</div>
			</div>

		</form>
	</div>
</script>

<div class="modal fade" id="motion_instructions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Installing Motion</h4>
			</div>
			<div class="modal-body">
				<p>To install a motion server you need to do the following:</p>
				<p>Open a terminal and run this command:</p>
				<p><i>sudo apt-get install motion</i></p>

				<p>Now motion must be configured:</p>
				<p>Run the following and set "start_motion_daemon" to "yes" so that motion will run automatically</p>
				<p><i>sudo nano /etc/default/motion</i></p>
				<p>Open the motion config file and with the following command:
				<p><i>sudo nano /etc/motion/motion.conf</i></p>
				<p>Change to following:</p>

				<p><i>setup_mode off</i></p>
				<p><i>control_localhost off</i></p>
				<p><i>control_html_output off</i></p>

				<p>
					Uncomment this line and add your desired username and password:
					<i>control_authentication username:password</i>
				</p>
				<p>Now start motion by running:</p>
				<p><i>sudo service motion start</i></p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>