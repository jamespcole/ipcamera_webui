<?
	require_once('api/config.php');
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
	$port = (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : '';
	$base_url = $protocol.$_SERVER['HTTP_HOST'].$port.str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']);
	//define('BASE_URL', $base_url);
	//print_r($_SERVER);
	//echo BASE_URL;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>IP Cameras</title>
    <base href="<?=BASE_URL?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?=BASE_URL?>styles/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=BASE_URL?>styles/style.css" rel="stylesheet">
    <link href="<?=BASE_URL?>styles/animate.min.css" rel="stylesheet">

    <script type="text/javascript">
    var BASE_URL = '<?=BASE_URL?>';
    var FIRST_RUN = <?=json_encode(FIRST_RUN)?>;
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
		<nav class="navbar navbar-default" role="navigation">
		  <!-- Brand and toggle get grouped for better mobile display -->
		  <div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		      <span class="sr-only">Toggle navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="#">IP Cams</a>
		  </div>

		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="<?=BASE_URL?>camera_list" id="cameras_link">Cameras</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		      <li><a href="<?=BASE_URL?>settings" id="settings_link"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
		    </ul>
		  </div><!-- /.navbar-collapse -->
		</nav>
    <div class="container">
    	<div id="global_error">

    	</div>
		<div id="camera_list" class="app-page">

		</div>
		<div id="settings" class="app-page">

		</div>
		<div id="add_camera" class="app-page">

		</div>
		<div id="view_camera" class="app-page">

		</div>
		<div id="save_success" class="app-page">

		</div>
		<div id="firstrun" class="app-page">

		</div>
		<div id="prerequisite_check" class="app-page">

		</div>
		<div id="user_settings" class="app-page">

		</div>
		<div id="firstrun_user_settings" class="app-page">

		</div>
		<div id="edit_motion" class="app-page">

		</div>
		<div id="import_motion_cameras" class="app-page">

		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?=BASE_URL?>scripts/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?=BASE_URL?>styles/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?=BASE_URL?>scripts/handlebars-v1.1.2.js"></script>
    <script src="<?=BASE_URL?>scripts/app.js"></script>
     <script src="<?=BASE_URL?>scripts/ui/settings.js"></script>
    <script src="<?=BASE_URL?>scripts/jquery.history.js"></script>

    <script id="global_error-template" type="text/x-handlebars-template">
		<div class="row">
			<div class="alert alert-danger col-sm-offset-2 col-sm-5">
				<div class="row">
					<div class="col-sm-12">
						<strong>{{message}}</strong>
						<p>{{reason}}</p>
					</div>
				</div>
			</div>
		</div>
	</script>
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
	<? include('includes/ui/camera_list.php'); ?>
	<? include('includes/ui/firstrun.php'); ?>
	<? include('includes/ui/settings.php'); ?>
	<? include('includes/ui/edit_motion.php'); ?>
	<? include('includes/ui/import_motion_cameras.php'); ?>
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
		</div>

	</script>
	<script id="add_camera-template" type="text/x-handlebars-template">
	    <div class="row col-sm-12">
	    	<div class="row">
		    	<h2 class=""><span class="glyphicon glyphicon-cog"></span> Camera Settings</h2>
		 		<hr class="col-sm-8" style="margin-top:0px;" />
	    	</div>
			<form role="form" class="form-horizontal" id="configuration_form">
				<input type="hidden" id="edit_image_url" name="image_url" />
				<input type="hidden" id="edit_camera_id" name="id" value="{{id}}" />
				<input type="hidden" id="edit_camera_base_url" name="base_url" value="{{base_url}}" />
				<input type="hidden" id="edit_camera_is_motion_stream" name="is_motion_stream" value="{{is_motion_stream}}" />
				<input type="hidden" id="edit_camera_motion_id" name="motion_id" value="{{motion_id}}" />
				<input type="hidden" id="edit_camera_thread_number" name="thread_number" value="{{thread_number}}" />
				<div class="form-group camera-name-field">
					<label for="edit_camera_name" class="col-sm-2 control-label">Name</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="edit_camera_name" name="name" placeholder="Enter Camera Name" value="{{name}}">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group camera-server-field">
					<label for="edit_camera_ip" class="col-sm-2 control-label">Server/IP</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="edit_camera_ip" name="server" placeholder="0.0.0.0" value="{{server}}">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group camera-port-field">
					<label for="edit_camera_port" class="col-sm-2 control-label">Port Number</label>
					<div class="col-sm-5">
						<input type="number" class="form-control" id="edit_camera_port" name="port" placeholder="Server Port" value="{{port}}">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group">
					<label for="edit_camera_username" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="edit_camera_username" name="username" placeholder="Username" value="{{username}}">
					</div>
				</div>
				<div class="form-group">
					<label for="edit_camera_password" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="edit_camera_password" name="password" placeholder="Password" value="{{password}}">
					</div>
				</div>
				<div class="form-group camera-camera_image-field">
					<label for="edit_camera_image" class="col-sm-2 control-label">Image Url</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="edit_camera_image" name="camera_image" placeholder="Image Url" value="{{camera_image}}">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<input type="checkbox" id="edit_proxy_data" name="proxy_data"> Proxy all requests (Recommended)
							</label>

						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<p class="help-block col-sm-7">
							Enable this option if you want access outside your local network and do not have external access configured for each individual camera.
							This option also provides greater security when accessing this application from external networks however it will cause slower updates to the camera feed.
						</p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						<h4>Camera Commands</h4>
						<p>
							If your camera supports features such as pan, tilt, zoom etc. you can configure them here.
							Click the "Add Camera Command" button to add a new command
						</p>
					</div>
				</div>
				<div id="command_list">

				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						<a class="btn btn-primary add-command">Add Camera Command</a>
					</div>
				</div>

				<div class="form-group" style="display:none" id="configuration_test_output">
					<div class="alert alert-success col-sm-offset-2 col-sm-5">
						<div class="row">
							<div class="col-sm-3">
								<img class="img-thumbnail" id="config_test_image" onError="App.configurationError({})" onLoad="App.configurationSuccess()" />
							</div>
							<div class="col-sm-9">
								<strong>Test successfull</strong>
								<p>Your configuration settings are correct and the camera was contacted successfully.</p>
							</div>
						</div>
					</div>
					<div class="alert alert-danger col-sm-offset-2 col-sm-5">
						<div class="row">
							<div class="col-sm-12">
								<strong>Test Failed</strong>
								<p>Your camera could not be found.  Please ensure that all settings are correct.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						<hr />
						<div class="pull-right">
							<a class="btn btn-success" id="test_config_btn">Test Settings</a>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
						<a href="." class="btn btn-default">Cancel</a>
					</div>
				</div>

			</form>
		</div>
	</script>
	<script id="command-template" type="text/x-handlebars-template">
		<div class="command-form">
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<h4>Command {{command_display_index}}</h4>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Button Text</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="button_text[]" placeholder="Button Text" value="{{button_text}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Icon</label>
				<div class="col-sm-5">
					<select class="form-control command-icon-select" name="command_icon[]">
						<option value="">None</option>
						<option value="glyphicon glyphicon-circle-arrow-left">Left</option>
						<option value="glyphicon glyphicon-circle-arrow-right">Right</option>
						<option value="glyphicon glyphicon-circle-arrow-up">Up</option>
						<option value="glyphicon glyphicon-circle-arrow-down">Down</option>
					</select>
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Command URL</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="command_url[]" placeholder="Command URL" value="{{command_url}}">
				</div>
				<span class="help-block validation-message col-sm-5" style="display:none;"></span>
			</div>
		</div>
	</script>
	<script id="prerequisite_check-template" type="text/x-handlebars-template">
		<div class="col-md-offset-2 col-md-8">
			<h1>Pre-flight Check</h1>
			<p>Just checking that everything is configured ok</p>
			{{#each checks}}
				<div class="alert alert-{{result}}">
					<strong>{{name}}</strong>
					<p>{{description}}</p>
					<p>{{resolution}}</p>
				</div>
			{{/each}}

			<p>
				{{#if has_errors}}
					<a class="btn btn-lg btn-danger pull-right try-again-btn" role="button">Try Again</a>
				{{else}}
					<a class="btn btn-lg btn-primary pull-right" href="firstrun_user_settings" role="button">Next</a>
				{{/if}}
			</p>
		</div>
	</script>
	<script id="user_settings-template" type="text/x-handlebars-template">
		<div class="col-md-offset-2 col-md-8">
			<div class="col-md-12 form-heading">
				<h1><span class="glyphicon glyphicon-user"></span> User Details</h1>
				<hr class="col-md-8" />
			</div>

			<form role="form" class="form-horizontal user-settings-form">
				<div class="form-group user-username-field">
					<label for="user_name" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="user_name" name="username" placeholder="User Name" value="{{username}}">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group user-password-field">
					<label for="user_password" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="user_password" name="password" placeholder="Password" value="">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group user-confirm_password-field">
					<label for="confirm_password" class="col-sm-2 control-label">Confirm</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="">
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group user-date_format-field">
					<label class="col-sm-2 control-label">Date Format</label>
					<div class="col-sm-5">
						<select class="form-control command-icon-select" name="date_format">
							<option value="DD/MM/YYYY">DD/MM/YYYY</option>
							<option value="YYYY-MM-DD">YYYY-MM-DD</option>
							<option value="MM/DD/YYYY">MM/DD/YYYY</option>
						</select>
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<div class="form-group user-time_format-field">
					<label class="col-sm-2 control-label">Time Format</label>
					<div class="col-sm-5">
						<label class="checkbox-inline">
							<input type="radio" name="time_format" class="time_24" value="HH:mm">
							24 Hour
						</label>
						<label class="checkbox-inline">
							<input type="radio" name="time_format" class="time_12" value="hh:mmA">
							12 Hour
						</label>
					</div>
					<span class="help-block validation-message col-sm-5" style="display:none;"></span>
				</div>
				<p class="col-md-7">
					<button type="submit" class="btn btn-primary pull-right">Save</button>
				</p>
			</form>
		</div>
	</script>
  </body>
</html>