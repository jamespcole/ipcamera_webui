<script id="view_camera-template" type="text/x-handlebars-template">
	<div class="row camera-view">
		<div class="well col-md-12">
			<div>
				<h4>
					{{name}}

					<div class="visible-sm visible-xs pull-right">
						<button class="btn btn-primary btn-xs pause-detection-btn" style="display:none"><span class="glyphicon glyphicon-eye-close"></span> Pause detection</button>
						<button class="btn btn-primary btn-xs resume-detection-btn" style="display:none"><span class="glyphicon glyphicon-eye-open"></span> Resume detection</button>
					</div>
				</h4>
			</div>
			<img src="{{image_url}}" class="img-thumbnail center-block" id="active_camera_image" />

			<div class="">
				<span class="visible-md visible-lg">
					<button class="btn btn-primary btn-sm pull-right pause-detection-btn" style="display:none"><span class="glyphicon glyphicon-eye-close"></span> Pause motion detection</button>
					<button class="btn btn-primary btn-sm pull-right resume-detection-btn" style="display:none"><span class="glyphicon glyphicon-eye-open"></span> Resume motion detection</button>
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
	<button class="btn btn-primary col-md-offset-2 col-md-8 col-sm-12 col-xs-12 more-events-btn" data-page="1" data-rpp="9" data-camera-id="{{id}}">More</button>
</script>