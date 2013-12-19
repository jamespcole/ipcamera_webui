<script id="settings-template" type="text/x-handlebars-template">
	<div class="row col-sm-offset-2 col-sm-6">
		<a href="<?=BASE_URL?>add_camera" class="btn btn-success new-camera-link"><span class="glyphicon glyphicon-plus-sign"></span> Add a new Camera</a>
		<h2>Cameras</h2>
		<table class="table">
			<tbody>
			{{#each cameras}}
				<tr>
					<td><img style="max-height:40px" src="{{image_url}}" class="img-thumbnail" /></td>
					<td><h4>{{name}}</h4></td>
					<td>
						<div class="pull-right">
							<a class="btn btn-danger delete-camera-link" data-camera-id="{{id}}">Delete</a>
							<a href="<?=BASE_URL?>edit_camera/{{id}}" class="btn btn-primary edit-camera-link" data-camera-id="{{id}}">Edit</a>
						</div>
					</td>
				</tr>
			{{/each}}
			</tbody>
		</table>
	</div>
</script>