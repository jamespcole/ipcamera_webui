<script id="save_success-template" type="text/x-handlebars-template">
	<div class="row">
		<div class="alert alert-success col-sm-offset-2 col-sm-6">
			<div class="row">
				<div class="col-sm-3">
					<img class="img-thumbnail" src={{image_url}} />
				</div>
				<div class="col-sm-9">
					{{#if is_new}}
						<strong>Camera Created</strong>
						<p>Your new camera has been added successfully</p>
					{{else}}
						<strong>Camera Saved Successfully</strong>
						<p>Your camera configuration has been updated</p>
					{{/if}}
					<div class="col-sm-12">
						<a class="btn btn-success" href="<?=BASE_URL?>add_camera">Add Another Camera</a>
						<a href="<?=BASE_URL?>" class="btn btn-primary pull-right">Go to Dashboard</a>
					</div>
				</div>

			</div>

		</div>
	</div>
</script>