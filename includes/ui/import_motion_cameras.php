<script id="import_motion_cameras-template" type="text/x-handlebars-template">
	<div class="row col-sm-offset-2 col-sm-6">
		{{#unless config_check.can_write}}
			<div class="alert alert-danger">
				<strong>The motion config file is not writable.</strong>
				<p>In order to manage the cameras in motion this app needs write permissions to the motion config file.</p>
				<p>To fix this error open a terminal and run the following commands:</p>
				<p><i>sudo usermod -a -G motion www-data</i></p>
				<p><i>sudo chmod g+w /etc/motion/motion.conf</i></p>
				<p><i>sudo service apache2 restart</i></p>

			</div>
			<a class="btn btn-lg btn-danger pull-right try-again-motion-btn" role="button" data-motion-id="{{motion_id}}">Try Again</a>
		{{/unless}}
		{{#if config_check.can_write}}
			<h2>Motion Server Cameras</h2>
			{{#if threads}}
				<table class="table">
					<tbody>
					{{#each threads}}
						<tr>
							<td><img style="max-height:40px" src="{{image}}" class="img-thumbnail" /></td>
							<td><h4>{{name}}</h4></td>
							<td>
								<div class="pull-right">
									<a href="<?=BASE_URL?>import_camera/{{motion_id}}/{{number}}" class="btn btn-success import-camera-link" data-motion-id="{{motion_id}}">Import</a>
								</div>
							</td>
						</tr>
					{{/each}}
					</tbody>
				</table>
			{{else}}
				<div class="alert alert-info">
					<strong>There are no active cameras configured on your motion server.</strong>
					<p>You can now add cameras to your motion server when creating or editing a camera.</p>
				</div>
				<a href="camera_list" class="btn btn-primary pull-right" role="button" data-motion-id="{{motion_id}}">Continue</a>

			{{/if}}
		{{/if}}
	</div>
</script>