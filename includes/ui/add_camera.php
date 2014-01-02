<script id="add_camera-template" type="text/x-handlebars-template">
    <div class="row col-md-offset-2 col-md-10 camera-settings">
    	<div class="row col-md-12 form-heading">
	    	<h1 class=""><span class="glyphicon glyphicon-cog"></span> Camera Settings</h1>
	 		<hr class="col-sm-8" />
    	</div>
		<form role="form" class="form-horizontal" id="configuration_form">
			<input type="hidden" id="edit_image_url" name="image_url" />
			<input type="hidden" id="edit_update_commands" name="update_commands" value="true" />
			<input type="hidden" id="edit_camera_id" name="id" value="{{id}}" />
			<input type="hidden" id="edit_camera_base_url" name="base_url" value="{{base_url}}" />
			<input type="hidden" id="edit_camera_is_motion_stream" name="is_motion_stream" value="{{is_motion_stream}}" />
			<!--<input type="hidden" id="edit_camera_motion_id" name="motion_id" value="{{motion_id}}" />-->
			<input type="hidden" id="edit_camera_thread_number" name="thread_number" value="{{thread_number}}" />
			<div class="form-group camera-motion_server-field">
				<label for="edit_camera_model" class="col-md-2 col-sm-2 control-label">Camera Type</label>
				<div class="col-md-5 col-sm-7">
					<select class="form-control" id="edit_camera_model_id" name="model_id">
						<option value="" data-index="-1">Generic</option>
						{{#each camera_models.cameras}}
							<option data-index="{{@index}}" value="{{id}}">{{brand}} - {{name}}</option>
						{{/each}}
					</select>
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group camera-name-field">
				<label for="edit_camera_name" class="col-md-2 col-sm-2 control-label">Name</label>
				<div class="col-md-5 col-sm-7">
					<input type="text" class="form-control" id="edit_camera_name" name="name" placeholder="Enter Camera Name" value="{{name}}">
				</div>
				<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2">Protocol</label>
				<div class="col-md-5 col-sm-7">
					<select class="form-control" name="protocol" id="edit_camera_protocol">
						<option value="http">HTTP</option>
						<option value="https">HTTPS</option>
					</select>
				</div>
				<span class="help-block validation-message col-md-5 col-sm-4" style="display:none;"></span>
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
			{{#if motion_servers.length}}
				<div class="form-group camera-motion_server-field">
					<label for="edit_camera_motion_server" class="col-md-2 col-sm-2 control-label">Motion Server</label>
					<div class="col-md-5 col-sm-7">
						<select class="form-control" id="edit_camera_motion_id" name="motion_id">
							<option value="">None</option>
							{{#each motion_servers}}
								<option value="{{id}}">{{name}}</option>
							{{/each}}
						</select>
					</div>
					<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
				</div>
			{{/if}}
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
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" id="edit_show_advanced"> Show advanced options
						</label>
					</div>
				</div>
			</div>
			<div class="form-group advanced-camera-option">
				<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
					<h4>Camera Commands</h4>
					<p>
						If your camera supports features such as pan, tilt, zoom etc. you can configure them here.
						Click the "Add Camera Command" button to add a new command
					</p>
				</div>
			</div>
			<div id="command_list" class="advanced-camera-option">

			</div>
			<div class="form-group advanced-camera-option">
				<div class="col-sm-offset-2 col-sm-5">
					<a class="btn btn-primary add-command">Add Camera Command</a>
				</div>
			</div>
			<div class="form-group advanced-camera-option">
				<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
					<h4>Status Parsers</h4>
					<p>
						You can retireve status information about your camera which you can display
						or use in your camera commands.
						Click the "Add Status Parser" button to add a new parser
					</p>
				</div>
			</div>
			<div id="status_handler_list" class="advanced-camera-option">

			</div>
			<div class="form-group advanced-camera-option">
				<div class="col-sm-offset-2 col-sm-5">
					<a class="btn btn-primary add-status-parser">Add Status Parser</a>
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
			<div class="col-sm-offset-2 col-sm-10 col-md-5">
				<h4>Command {{command_display_index}} <button type="button" class="btn btn-danger btn-xs pull-right remove-command-btn">Remove</button></h4>
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
					<option value="glyphicon glyphicon-off">Power</option>
					<option value="glyphicon glyphicon-remove-circle">Remove Circle</option>
					<option value="glyphicon glyphicon-certificate">Certificate</option>
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
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Status Handler</label>
			<div class="col-md-5 col-sm-7">
				<textarea class="form-control accepts_tab status-handler" rows="4" name="status_handler[]">{{status_handler}}</textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Before Command</label>
			<div class="col-md-5 col-sm-7">
				<textarea class="form-control accepts_tab before-command-handler" rows="4" name="before_command_handler[]">{{before_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">After Command</label>
			<div class="col-md-5 col-sm-7">
				<textarea class="form-control accepts_tab after-command-handler" rows="4" name="after_command_handler[]">{{after_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
	</div>
</script>
<script id="status_handler-template" type="text/x-handlebars-template">
	<div class="status-handler-form">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10 col-md-5">
				<h4>Status Handler  <button type="button" class="btn btn-danger btn-xs pull-right remove-status-handler-btn">Remove</button></h4>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Button Text</label>
			<div class="col-md-5 col-sm-7">
				<input type="text" class="form-control status-url" name="status_url[]" placeholder="Button Text" value="{{status_url}}">
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="col-md-2 col-sm-2 control-label">Parser</label>
			<div class="col-md-5 col-sm-7">
				<textarea class="form-control accepts_tab status-parser" rows="12" name="status_parser[]">{{status_parser}}</textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group" style="display:none">
			<label class="col-md-2 col-sm-2 control-label">Test Output</label>
			<div class="col-md-5 col-sm-7">
				<textarea class="form-control accepts_tab status-parser-result" rows="12" disabled="disabled"></textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-5 col-sm-offset-2 col-sm-7">
				<hr />
				<div class="pull-right">
					<a class="btn btn-success test-handler-btn">Test Handler</a>
				</div>
			</div>
		</div>
	</div>
</script>