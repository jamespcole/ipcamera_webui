<script id="view_event-template" type="text/x-handlebars-template">
	<div class="row well">
		{{dateTimeFormat start date_type="timestamp"}} - {{timeFormat end date_type="timestamp"}}
		<div>
			<img src="" class="img-thumbnail center-block" id="event_video" />
		</div>
		<!--<div class="progress">
		  <div class="progress-bar video-progress" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
		    <span class="sr-only">60% Complete</span>
		  </div>
		</div>-->
		<p>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="center-block">
					<button class="btn btn-primary backward-btn"><span class="glyphicon glyphicon-backward"></span></button>
					<button class="btn btn-primary pause-btn"><span class="glyphicon glyphicon-pause"></span></button>
					<button class="btn btn-primary play-btn" style="display:none"><span class="glyphicon glyphicon-play"></span></button>
					<button class="btn btn-primary forward-btn"><span class="glyphicon glyphicon-forward"></span></button>
				</div>
			</div>
			<div class="col-md-10 col-sm-8 col-xs-6">
				<input type="text" class="col-md-12 col-sm-12 col-xs-12 progress-slider"></div>
			</div>
		</p>
	</div>
	<div class="row">
		{{#each images}}
		<div class="col-md-4 col-sm-4">
			<div class="well">
				<img  class="img-thumbnail event-frame" src="{{image_url}}" />
			</div>
		</div>
		{{/each}}
	</div>
</script>