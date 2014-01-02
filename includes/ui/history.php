<script id="history-template" type="text/x-handlebars-template">
	{{#each events}}
		<div class="col-md-4 col-sm-4">
			<div class="well">
				{{dateTimeFormat start date_type="timestamp"}}
					<img  class="img-thumbnail" src="{{preview}}" />
				<a href="view_event/{{camera_id}}/{{date}}/{{event_id}}" class="btn btn-primary btn-sm">View</a>
			</div>
		</div>
	{{/each}}
</script>
<div class="modal fade" id="event_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Event Info</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>

<script id="event_info-template" type="text/x-handlebars-template">
	{{#each images}}
	<div class="col-md-4 col-sm-4">
		<div class="well">
			<img  class="img-thumbnail" src="{{image_url}}" />
		</div>
	</div>
	{{/each}}
</script>