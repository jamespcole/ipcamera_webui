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
				<div class="well well-sm image-loading camera-list-img">
					<h4 class="pull-left">{{name}}</h4><a href="<?=BASE_URL?>view_camera/{{id}}" class="btn btn-primary btn-sm pull-right camera-view-btn" data-camera-id="{{id}}">View</a>
					<img src="" class="img-thumbnail camera-img camera-stream" data-stream-url="<?=BASE_URL?>{{image_url}}" data-snapshot-url="<?=BASE_URL?>{{snapshot_url}}" id="image_{{id}}" />
					<div class="img-thumbnail img-placeholder">
						<div class="img-loading-text">Loading Camera...</div>
						<div class="col-md-offset-2 col-md-8 progress progress-striped active">
							<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		{{/each}}
	</div>
</script>