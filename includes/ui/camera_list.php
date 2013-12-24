<script id="camera_list-template" type="text/x-handlebars-template">
	<div class="row">
		{{#unless cameras.length}}
			<div class="jumbotron">
				<h1>Configure Cameras</h1>
				<p>It looks like you do not have any cameras configured yet.</p>
				<p>If you are running a motion server you can import your existing cameras from there.</p>
				<p>If you do not want to use a motion server you can add your cameras manually.</p>
				<p>
					<a class="btn btn-lg btn-success check-btn" role="button" href="add_camera"><span class="glyphicon glyphicon-plus-sign"></span> Add a new Camera</a>
					<a class="btn btn-lg btn-primary check-btn" role="button" href="motion_servers"><span class="glyphicon glyphicon-cloud-download"></span> Import Cameras from Motion</a>
				</p>
			</div>
		{{/unless}}
		{{#each cameras}}
			<div class="col-md-4 col-sm-4">
				<div class="well">
					<h4 class="pull-left">{{name}}</h4><a href="<?=BASE_URL?>view_camera/{{id}}" class="btn btn-primary btn-sm pull-right camera-view-btn" data-camera-id="{{id}}">View</a>
					<img src="{{image_url}}" class="img-thumbnail" id="image_{{id}}" />
				</div>
			</div>
		{{/each}}
	</div>
</script>