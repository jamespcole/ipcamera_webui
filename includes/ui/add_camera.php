<script id="add_camera-template" type="text/x-handlebars-template">
    <div class="row col-md-offset-2 col-md-10">
    	<div class="row col-md-12 form-heading">
	    	<h1 class=""><span class="glyphicon glyphicon-cog"></span> Camera Settings</h1>
	 		<hr class="col-sm-8" />
    	</div>
		<form role="form" class="form-horizontal" id="configuration_form">
			<input type="hidden" id="edit_image_url" name="image_url" />
			<input type="hidden" id="edit_camera_id" name="id" value="{{id}}" />
			<input type="hidden" id="edit_camera_base_url" name="base_url" value="{{base_url}}" />
			<input type="hidden" id="edit_camera_is_motion_stream" name="is_motion_stream" value="{{is_motion_stream}}" />
			<input type="hidden" id="edit_camera_motion_id" name="motion_id" value="{{motion_id}}" />
			<input type="hidden" id="edit_camera_thread_number" name="thread_number" value="{{thread_number}}" />
			<div class="form-group camera-name-field">
				<label for="edit_camera_name" class="col-md-2 col-sm-2 control-label">Name</label>
				<div class="col-md-5 col-sm-7">
					<input type="text" class="form-control" id="edit_camera_name" name="name" placeholder="Enter Camera Name" value="{{name}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group camera-server-field">
				<label for="edit_camera_ip" class="col-md-2 col-sm-2 control-label">Server/IP</label>
				<div class="col-md-5 col-sm-7">
					<input type="text" class="form-control" id="edit_camera_ip" name="server" placeholder="0.0.0.0" value="{{server}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group camera-port-field">
				<label for="edit_camera_port" class="col-md-2 col-sm-2 control-label">Port Number</label>
				<div class="col-md-5 col-sm-7">
					<input type="number" class="form-control" id="edit_camera_port" name="port" placeholder="Server Port" value="{{port}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label for="edit_camera_username" class="col-md-2 col-sm-2 control-label">Username</label>
				<div class="col-md-5 col-sm-7">
					<input type="text" class="form-control" id="edit_camera_username" name="username" placeholder="Username" value="{{username}}">
				</div>
			</div>
			<div class="form-group">
				<label for="edit_camera_password" class="col-md-2 col-sm-2 control-label">Password</label>
				<div class="col-md-5 col-sm-7">
					<input type="password" class="form-control" id="edit_camera_password" name="password" placeholder="Password" value="{{password}}">
				</div>
			</div>
			<div class="form-group camera-camera_image-field">
				<label for="edit_camera_image" class="col-md-2 col-sm-2 control-label">Image Url</label>
				<div class="col-md-5 col-sm-7">
					<input type="text" class="form-control" id="edit_camera_image" name="camera_image" placeholder="Image Url" value="{{camera_image}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group" style="display:none">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" id="edit_proxy_data" name="proxy_data"> Proxy all requests (Recommended)
						</label>

					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-10">
					<p class="help-block col-md-6 col-sm-9">
						Enable this option if you want access outside your local network and do not have external access configured for each individual camera.
						This option also provides greater security when accessing this application from external networks however it will cause slower updates to the camera feed.
					</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
					<h4>Camera Commands</h4>
					<p>
						If your camera supports features such as pan, tilt, zoom etc. you can configure them here.
						Click the "Add Camera Command" button to add a new command
					</p>
				</div>
			</div>
			<div id="command_list">

			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-5">
					<a class="btn btn-primary add-command">Add Camera Command</a>
				</div>
			</div>

			<div class="form-group" style="display:none" id="configuration_test_output">
				<div class="alert alert-success col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
					<div class="row">
						<div class="col-sm-3">
							<img class="img-thumbnail" id="config_test_image" onError="App.configurationError({})" onLoad="App.configurationSuccess()" />
						</div>
						<div class="col-sm-9">
							<strong>Test successfull</strong>
							<p>Your configuration settings are correct and the camera was contacted successfully.</p>
						</div>
					</div>
				</div>
				<div class="alert alert-danger col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
					<div class="row">
						<div class="col-sm-12">
							<strong>Test Failed</strong>
							<p>Your camera could not be found.  Please ensure that all settings are correct.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
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
<script id="command-template" type="text/x-handlebars-template">
	<div class="command-form">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<h4>Command {{command_display_index}}</h4>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Button Text</label>
			<div class="col-md-5 col-sm-7">
				<input type="text" class="form-control" name="button_text[]" placeholder="Button Text" value="{{button_text}}">
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Icon</label>
			<div class="col-md-5 col-sm-7">
				<select class="form-control command-icon-select" name="command_icon[]">
					<option value="">None</option>
					<option value="glyphicon glyphicon-circle-arrow-left">Left</option>
					<option value="glyphicon glyphicon-circle-arrow-right">Right</option>
					<option value="glyphicon glyphicon-circle-arrow-up">Up</option>
					<option value="glyphicon glyphicon-circle-arrow-down">Down</option>
				</select>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Command URL</label>
			<div class="col-md-5 col-sm-7">
				<input type="text" class="form-control" name="command_url[]" placeholder="Command URL" value="{{command_url}}">
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
	</div>
</script>