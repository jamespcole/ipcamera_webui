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

    <link href="<?=BASE_URL?>scripts/vendor/slider/css/slider.css" rel="stylesheet">

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
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_nav">
		      <span class="sr-only">Toggle navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="camera_list"> <span class="glyphicon glyphicon-eye-open"></span> IP Cams</a>
		  </div>

		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="main_nav">
			<ul class="nav navbar-nav">
				<li class="active"><a href="<?=BASE_URL?>camera_list" id="cameras_link">Cameras</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		      <li><a href="<?=BASE_URL?>settings" id="settings_link"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
		      <li class="hidden-xs"><a href="" class="logout-btn"><span class="glyphicon glyphicon-log-out"></span></a></li>
		      <li class="visible-xs"><a href="" class="logout-btn"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
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
		<div id="motion_servers" class="app-page">

		</div>
		<div id="cameras" class="app-page">

		</div>
		<div id="login" class="app-page">

		</div>
		<div id="error_404" class="app-page">

		</div>
		<div id="view_event" class="app-page">

		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?=BASE_URL?>scripts/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?=BASE_URL?>styles/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?=BASE_URL?>scripts/handlebars-v1.1.2.js"></script>
    <script src="<?=BASE_URL?>scripts/simplerouter.js"></script>
    <script src="<?=BASE_URL?>scripts/moment.min.js"></script>
    <script src="<?=BASE_URL?>scripts/app.js"></script>
	<script src="<?=BASE_URL?>scripts/api/settings.js"></script>
	<script src="<?=BASE_URL?>scripts/api/motion.js"></script>
	<script src="<?=BASE_URL?>scripts/api/cameras.js"></script>
    <script src="<?=BASE_URL?>scripts/ui/settings.js"></script>
    <script src="<?=BASE_URL?>scripts/ui/cameras.js"></script>
    <script src="<?=BASE_URL?>scripts/jquery.history.js"></script>
    <script src="<?=BASE_URL?>scripts/vendor/slider/js/bootstrap-slider.js"></script>


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
	<? include('includes/ui/user_settings.php'); ?>
	<? include('includes/ui/motion_servers.php'); ?>
	<? include('includes/ui/cameras.php'); ?>
	<? include('includes/ui/add_camera.php'); ?>
	<? include('includes/ui/login.php'); ?>
	<? include('includes/ui/history.php'); ?>
	<? include('includes/ui/error_404.php'); ?>
	<? include('includes/ui/view_camera.php'); ?>
	<? include('includes/ui/view_event.php'); ?>

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
  </body>
</html>