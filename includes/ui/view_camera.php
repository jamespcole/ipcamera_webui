<script id="view_camera-template" type="text/x-handlebars-template">
	<div class="row camera-view">
		<div class="well col-md-12 image-loading">
			<div>
				<h4>
					{{name}}

					<div class="visible-sm visible-xs pull-right">
						<button class="btn btn-primary btn-xs pause-detection-btn motion-detection-btn" style="display:none" data-camera-id="{{id}}" data-status="pause"><span class="glyphicon glyphicon-eye-close"></span> Pause detection</button>
						<button class="btn btn-primary btn-xs resume-detection-btn motion-detection-btn" style="display:none" data-camera-id="{{id}}" data-status="start"><span class="glyphicon glyphicon-eye-open"></span> Resume detection</button>
						<div class="status-updating-loader" style="display:none">Updating...</div>
					</div>
				</h4>
			</div>
			<img src="" data-stream-url="{{image_url}}" data-snapshot-url="{{snapshot_url}}" class="img-thumbnail center-block camera-stream" id="active_camera_image" />
			<div class="img-thumbnail img-placeholder">
				<div class="img-loading-text">Loading Camera...</div>
				<div class="col-md-offset-2 col-md-8 progress progress-striped active">
					<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span class="sr-only">100% Complete</span>
					</div>
				</div>
			</div>

			<div class="">
				<span class="visible-md visible-lg">
					<button class="btn btn-primary btn-sm pull-right pause-detection-btn motion-detection-btn" style="display:none" data-camera-id="{{id}}" data-status="pause"><span class="glyphicon glyphicon-eye-close"></span> Pause motion detection</button>
					<button class="btn btn-primary btn-sm pull-right resume-detection-btn motion-detection-btn" style="display:none" data-camera-id="{{id}}" data-status="start"><span class="glyphicon glyphicon-eye-open"></span> Resume motion detection</button>
					<div class="status-updating-loader" style="display:none">Updating...</div>
				</span>
				{{#if commands}}
					<div class="col-md-12">
						{{#each commands}}
								<a class="camera-command btn btn-default" data-command-url="{{full_command_url}}">
									{{#if command_icon}}
										<span class="{{command_icon}}"></span>
									{{/if}}
									{{button_text}}
								</a>
						{{/each}}
					</div>
				{{/if}}
			</div>
		</div>
	</div>
	<div class="row" id="camera_history">

	</div>
	{{#if motion_id}}
		<button class="btn btn-primary col-md-offset-2 col-md-8 col-sm-12 col-xs-12 more-events-btn" data-page="1" data-rpp="9" data-camera-id="{{id}}">More</button>
	{{/if}}
</script>