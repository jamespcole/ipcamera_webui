<script id="import_motion_cameras-template" type="text/x-handlebars-template">
	<div class="row col-sm-offset-2 col-sm-6">
		<h2>Motion Server Cameras</h2>
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
	</div>
</script>