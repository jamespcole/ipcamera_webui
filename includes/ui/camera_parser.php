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
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary save-status-parser-edit-modal">Save</button>
			</div>
		</div>
	</div>
</div>