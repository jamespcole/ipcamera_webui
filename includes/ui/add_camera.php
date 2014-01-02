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
				<div class="col-md-10">
					<h4>Camera Commands</h4>
					<p>
						If your camera supports features such as pan, tilt, zoom etc. you can configure them here.
						Click the "Add Camera Command" button to add a new command
					</p>
				</div>
			</div>
			<div class="form-group advanced-camera-option col-md-10">
				<table class="table" id="commands_table">
					<tbody>

					</tbody>
				</table>
				<a class="btn btn-success add-command pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add Camera Command</a>
			</div>

			<div class="form-group col-md-10 col-sm-7 advanced-camera-option">
				<h4>Status Parsers</h4>
				<p>
					You can retireve status information about your camera which you can display
					or use in your camera commands.
					Click the "Add Status Parser" button to add a new parser
				</p>
			</div>

			<div class="form-group advanced-camera-option col-md-10">
				<table class="table" id="status_parsers_table">
					<tbody>

					</tbody>
				</table>
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
<script id="command-row-template" type="text/x-handlebars-template">
	<tr>
		<td>
			<span class="{{command_icon}}"></span> {{button_text}}
			<div class="command-form-fields">
				<input type="hidden" class="row_index" name="row_index[]" value="{{row_index}}" />
				<input type="hidden" class="button_text" name="button_text[]" value="{{button_text}}" />
				<input type="hidden" class="command_icon" name="command_icon[]" value="{{command_icon}}" />
				<input type="hidden" class="command_url" name="command_url[]" value="{{command_url}}" />
				<input type="hidden" class="status_handler" name="status_handler[]" value="{{status_handler}}" />
				<input type="hidden" class="before_command_handler" name="before_command_handler[]" value="{{before_command_handler}}" />
				<input type="hidden" class="after_command_handler" name="after_command_handler[]" value="{{after_command_handler}}" />
			</div>
		</td>
		<td class="hidden-xs">{{command_url}}</td>
		<td>
			<div class="pull-right">
				<button type="button" class="btn btn-primary btn-xs show-command-edit-modal">Edit</button>
				<button type="button" class="btn btn-danger btn-xs delete-command-btn">Delete</button>
			</div>
		</td>
	</tr>
</script>
<script id="command-template" type="text/x-handlebars-template">
	<div class="command-form">
		<input type="hidden" class="row_index" value="{{row_index}}" />
		<div class="form-group">
			<label class="control-label">Button Text</label>
			<div class="">
				<input type="text" class="form-control button_text" name="button_text[]" placeholder="Button Text" value="{{button_text}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Icon</label>
			<div class="">
				<select class="form-control command-icon-select command_icon" name="command_icon[]">
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
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Command URL</label>
			<div class="">
				<input type="text" class="form-control command_url" name="command_url[]" placeholder="Command URL" value="{{command_url}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Status Handler</label>
			<div class="">
				<textarea class="form-control accepts_tab status-handler status_handler" rows="4" name="status_handler[]">{{status_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Before Command</label>
			<div class="">
				<textarea class="form-control accepts_tab before-command-handler before_command_handler" rows="4" name="before_command_handler[]">{{before_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">After Command</label>
			<div class="">
				<textarea class="form-control accepts_tab after-command-handler after_command_handler" rows="4" name="after_command_handler[]">{{after_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
	</div>
</script>

<script id="status_parser-template" type="text/x-handlebars-template">
	<div class="status-parser-form">
		<input type="hidden" class="row_index" value="{{row_index}}" />
		<div class="form-group">
			<label class="ccontrol-label">Status Url</label>
			<div class="">
				<input type="text" class="form-control status-url status_url" name="status_url[]" placeholder="Status Url" value="{{status_url}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Parser</label>
			<div class="">
				<textarea class="form-control accepts_tab status-parser status_parser" rows="12" name="status_parser[]">{{status_parser}}</textarea>
			</div>
			<span class="help-block validation-message col-md-5 col-sm-3" style="display:none;"></span>
		</div>
		<div class="form-group" style="display:none">
			<label class="control-label">Test Output</label>
			<div class="">
				<textarea class="form-control accepts_tab status-parser-result" rows="12" disabled="disabled"></textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<div class="">
				<hr />
				<div class="pull-right">
					<a class="btn btn-success test-parser-btn">Test Parser</a>
				</div>
			</div>
		</div>
	</div>
</script>
<script id="status-parser-row-template" type="text/x-handlebars-template">
	<tr>
		<td>
			{{status_url}}
			<div class="status-parser-form-fields">
				<input type="hidden" class="row_index" name="row_index[]" value="{{row_index}}" />
				<input type="hidden" class="status_url" name="status_url[]" value="{{status_url}}" />
				<input type="hidden" class="status_parser" name="status_parser[]" value="{{status_parser}}" />
			</div>
		</td>
		<td>
			<div class="pull-right">
				<button type="button" class="btn btn-primary btn-xs show-status-parser-edit-modal">Edit</button>
				<button type="button" class="btn btn-danger btn-xs delete-status-parser-btn">Delete</button>
			</div>
		</td>
	</tr>
</script>
<div class="modal fade" id="edit_command_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Command</h4>
			</div>
			<div class="modal-body">
				<form role="form" class="edit-command-form">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save-command-edit-modal" data-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="edit_status_parser_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Status Parser</h4>
			</div>
			<div class="modal-body">
				<form role="form" class="edit-status-parser-form">

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save-status-parser-edit-modal" data-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>