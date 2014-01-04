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
			<img src="" data-stream-url="<?=BASE_URL?>{{image_url}}" data-snapshot-url="<?=BASE_URL?>{{snapshot_url}}" class="img-thumbnail center-block camera-stream" id="active_camera_image" />
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
					<div class="col-md-12 camera-commands-list">
						<div class="camera-command-panel panel-normal col-md-4" data-control-size="{{control_size}}">
							<div class="camera-command-group group-normal" data-control-size="{{control_size}}">
								{{#each commands}}
										{{#equal command_type "button" }}
											<a type="button" role="button" class="camera-command btn btn-default camera-command-{{command_type}} control-size-{{control_size}}" data-command-index="{{@index}}" data-command-url="{{command_url}}" data-camera-id="{{../../id}}">
												{{#if command_icon}}
													<span class="{{command_icon}}"></span>
												{{/if}}
												<span class="command-text">
													{{button_text}}
												</span>
											</a>
										{{/equal}}
										{{#equal command_type "placeholder" }}
											<a type="button" role="button" class="camera-command btn btn-default camera-command-{{command_type}} control-size-{{control_size}}" data-command-index="{{@index}}" data-command-url="{{command_url}}" data-camera-id="{{../../id}}">
												<span class="command-text">
													--
												</span>
											</a>
										{{/equal}}
										{{#equal command_type "group" }}
											</div>
											<div class="camera-command-group group-{{group_type}}" data-control-size="{{control_size}}" data-command-index="{{@index}}">

										{{/equal}}
										{{#equal command_type "panel" }}
												</div>
											</div>
											<div class="camera-command-panel panel-{{group_type}} col-md-4" data-control-size="{{control_size}}" data-command-index="{{@index}}">
												<div class="camera-command-group group-normal" data-control-size="">
										{{/equal}}
										{{#equal command_type "text" }}
											<span class="camera-command camera-command-{{command_type}} control-size-{{control_size}}" data-command-index="{{@index}}" data-camera-id="{{../../id}}">
												{{#if command_icon}}
													<span class="{{command_icon}}"></span>
												{{/if}}
												<span class="command-text">
													{{button_text}}
												</span>
											</span>
										{{/equal}}
								{{/each}}
							</div>
						</div>
					</div>
				{{/if}}
			</div>
		</div>
	</div>
	<div class="row" id="camera_history">

	</div>
	{{#if motion_id}}
		<button class="btn btn-primary col-md-offset-2 col-md-8 col-sm-12 col-xs-12 more-events-btn" style="display:none" data-page="1" data-rpp="9" data-camera-id="{{id}}">More</button>
	{{/if}}
</script>