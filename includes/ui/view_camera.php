<script id="view_camera-template" type="text/x-handlebars-template">
	<div class="row well col-md-8 col-md-offset-2">
		<div class="col-md-12">
			<h4>{{name}}</h4>
			<img src="{{image_url}}" class="img-thumbnail col-md-12" id="active_camera_image" />
		</div>
		{{#if commands}}
			<div class="row">
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
			</div>
		{{/if}}
		<div id="camera_history">

		</div>
	</div>
</script>