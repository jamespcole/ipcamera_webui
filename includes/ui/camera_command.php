<script id="command-row-template" type="text/x-handlebars-template">
	<tr>
		<td>
			<span class="{{command_icon}}"></span> {{command_name}}
			<div class="command-form-fields">
				<input type="hidden" class="row_index" name="row_index[]" value="{{row_index}}" />
				<input type="hidden" class="button_text" name="button_text[]" value="{{button_text}}" />
				<input type="hidden" class="command_icon" name="command_icon[]" value="{{command_icon}}" />
				<input type="hidden" class="command_url" name="command_url[]" value="{{command_url}}" />
				<input type="hidden" class="status_handler" name="status_handler[]" value="{{status_handler}}" />
				<input type="hidden" class="before_command_handler" name="before_command_handler[]" value="{{before_command_handler}}" />
				<input type="hidden" class="after_command_handler" name="after_command_handler[]" value="{{after_command_handler}}" />
				<input type="hidden" class="command_type" name="command_type[]" value="{{command_type}}" />
				<input type="hidden" class="group_type" name="group_type[]" value="{{group_type}}" />
				<input type="hidden" class="control_size" name="control_size[]" value="{{control_size}}" />
				<input type="hidden" class="command_name" name="command_name[]" value="{{command_name}}" />
			</div>
		</td>
		<td class="hidden-xs">{{command_type}}</td>
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
			<label class="control-label">Name</label>
			<div class="">
				<input type="text" class="form-control command_name" name="command_name[]" placeholder="Command Name" value="{{command_name}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group">
			<label class="control-label">Type</label>
			<div class="">
				<select class="form-control command-type-select command_type" name="command_type[]">
					<option value="button">Button</option>
					<option value="text">Text</option>
					<option value="group">Group</option>
					<option value="placeholder">Placeholder</option>
					<option value="panel">Panel</option>
				</select>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-button hide-for-text hide-for-placeholder hide-for-panel">
			<label class="control-label">Group Type</label>
			<div class="">
				<select class="form-control command-group-type-select group_type" name="group_type[]">
					<option value="normal">Normal</option>
					<option value="grouped">Grouped</option>
					<option value="justified">Stacked</option>
					<option value="vertical">Vertical</option>
				</select>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-placeholder hide-for-panel">
			<label class="control-label">Button Text</label>
			<div class="">
				<input type="text" class="form-control button_text" name="button_text[]" placeholder="Button Text" value="{{button_text}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-placeholder hide-for-panel">
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
		<div class="form-group hide-for-placeholder hide-for-panel">
			<label class="control-label">Control Size</label>
			<div class="">
				<select class="form-control command-control-size-select control_size" name="control_size[]">
					<option value="">Inherit</option>
					<option value="lg">Large</option>
					<option value="md">Medium</option>
					<option value="sm">Small</option>
					<option value="xs">Tiny</option>
				</select>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-group hide-for-text hide-for-placeholder hide-for-panel">
			<label class="control-label">Command URL</label>
			<div class="">
				<input type="text" class="form-control command_url" name="command_url[]" placeholder="Command URL" value="{{command_url}}">
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-group hide-for-placeholder hide-for-panel">
			<label class="control-label">Status Handler</label>
			<div class="">
				<textarea class="form-control accepts_tab status-handler status_handler" rows="4" name="status_handler[]">{{status_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-group hide-for-text hide-for-placeholder hide-for-panel">
			<label class="control-label">Before Command</label>
			<div class="">
				<textarea class="form-control accepts_tab before-command-handler before_command_handler" rows="4" name="before_command_handler[]">{{before_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
		<div class="form-group hide-for-group hide-for-text hide-for-placeholder hide-for-panel">
			<label class="control-label">After Command</label>
			<div class="">
				<textarea class="form-control accepts_tab after-command-handler after_command_handler" rows="4" name="after_command_handler[]">{{after_command_handler}}</textarea>
			</div>
			<span class="help-block validation-message" style="display:none;"></span>
		</div>
	</div>
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
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary save-command-edit-modal">Save</button>
			</div>
		</div>
	</div>
</div>