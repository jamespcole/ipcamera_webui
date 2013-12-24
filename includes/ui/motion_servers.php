<script id="motion_servers-template" type="text/x-handlebars-template">
	<div class="row col-md-offset-2 col-md-8 col-sm-offset-0 col-sm-12">

		{{#if motion_servers}}
			<h2><span class="glyphicon glyphicon-cloud-download"></span> Motion Servers <a href="<?=BASE_URL?>add_motion_server" class="btn btn-success pull-right hidden-xs hidden-sm"><span class="glyphicon glyphicon-plus-sign"></span> Add a New Motion Server</a></h2>
			<table class="table">
				<tbody>
				{{#each motion_servers}}
					<tr>
						<td><h4>{{name}}</h4></td>
						<td>
							<div class="pull-right">
								<a href="<?=BASE_URL?>import_motion_cameras/{{id}}" class="btn btn-info btn-sm">Import Cameras</a>
								<a class="btn btn-danger btn-sm delete-motion-server-link" data-motion-id="{{id}}">Delete</a>
								<a href="<?=BASE_URL?>edit_motion_server/{{id}}" class="btn btn-primary btn-sm">Edit</a>
							</div>
						</td>
					</tr>
				{{/each}}
				</tbody>
			</table>
			<a href="<?=BASE_URL?>add_motion_server" class="btn btn-success hidden-mg hidden-lg"><span class="glyphicon glyphicon-plus-sign"></span> Add a New Motion Server</a
		{{else}}
			<a href="<?=BASE_URL?>add_motion_server" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Add a New Motion Server</a
		{{/if}}
	</div>
</script>