<script id="cameras-template" type="text/x-handlebars-template">
	<div class="row col-md-offset-2 col-md-8 col-sm-offset-0 col-sm-12">
		{{#if cameras}}

			<h2><span class="glyphicon glyphicon-facetime-video"></span> Cameras <a href="<?=BASE_URL?>add_camera" class="btn btn-success new-camera-link pull-right hidden-xs hidden-sm"><span class="glyphicon glyphicon-plus-sign"></span> Add a new Camera</a></h2>

			<table class="table">
				<tbody>
				{{#each cameras}}
					<tr>
						<td><img style="max-height:40px" src="{{snapshot_url}}" class="img-thumbnail" /></td>
						<td><h4>{{name}}</h4></td>
						<td>
							<div class="pull-right">
								<a class="btn btn-danger delete-camera-link btn-sm" data-camera-id="{{id}}">Delete</a>
								<a href="<?=BASE_URL?>edit_camera/{{id}}" class="btn btn-primary btn-sm">Edit</a>
							</div>
						</td>
					</tr>
				{{/each}}
				</tbody>
			</table>
			<a href="<?=BASE_URL?>add_camera" class="btn btn-success new-camera-link hidden-mg hidden-lg"><span class="glyphicon glyphicon-plus-sign"></span> Add a new Camera</a>
		{{else}}
			<a href="<?=BASE_URL?>add_camera" class="btn btn-success new-camera-link"><span class="glyphicon glyphicon-plus-sign"></span> Add a new Camera</a>
		{{/if}}
	</div>
</script>