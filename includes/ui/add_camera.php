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
			<div class="form-group advanced-camera-option col-md-10">
				<div class="panel panel-default">
					<div class="panel-heading">Camera Commands</div>
					<div class="panel-body">
						<p>
							If your camera supports features such as pan, tilt, zoom etc. you can configure them here.
							Click the "Add Camera Command" button to add a new command
						</p>
					</div>
					<table class="table sortable-table" id="commands_table">
						<thead>
							<tr>
								<th>Name</th>
								<th class="hidden-xs">Type</th>
								<th><span class="pull-right">Actions</span></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<a class="btn btn-success add-command pull-right col-xs-12"><span class="glyphicon glyphicon-plus-sign"></span> Add Camera Command</a>
			</div>

			<div class="form-group advanced-camera-option col-md-10">
				<div class="panel panel-default">
					<div class="panel-heading">Status Parsers</div>
					<div class="panel-body">
						<p>
							You can retireve status information about your camera which you can display
							or use in your camera commands.
							Click the "Add Status Parser" button to add a new parser
						</p>
					</div>
					<table class="table sortable-table" id="status_parsers_table">
						<thead>
							<tr>
								<th>Url</th>
								<th><span class="pull-right">Actions</span></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<a class="btn btn-success add-status-parser pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add Status Parser</a>
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