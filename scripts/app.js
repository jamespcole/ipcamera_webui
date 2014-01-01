var App = {
	API_URL: BASE_URL + 'api/',
	camera_updaters: [],
	current_user: null,
	ajax_requests: 0,
	camera_models: null,
	use_streams: true,

	renderCameras: function() {

		App.API.Cameras.getCameras().then(function(data) {
			var source   = $("#camera_list-template").html();
			var template = Handlebars.compile(source);
			var html    = template(data);
			$("#camera_list").html(html);
			App.changePage('camera_list');
			App.setActiveNav('cameras_link');
			App.camera_updaters = [];
			for(var i = 0; i < data.cameras.length; i++) {
				var camera = data.cameras[i];

				var element = $('#image_' + camera.id);
				element.on('load', function(event) {
					App.UI.Cameras.imageLoaded(this);
				});
				if(App.use_streams) {
					element.attr('src', element.data('stream-url'));

					element.on('error', function(event) {
						var target = $(this);
						var camera_id = camera.id;
						if(target.attr('src') == target.data('stream-url')) {
							console.log('loading stream failed, falling back to polling mode');

							var updater = new CameraUpdater({element: target, url: target.data('snapshot-url')});
							updater.init();
							updater.start();
							App.camera_updaters['updater_' + camera_id] = updater;
						}
					});
				}
				else {
					if(!App.camera_updaters['updater_' + camera.id]) {
						var updater = new CameraUpdater({element: element, url: element.data('snapshot-url')});
						updater.init();
						updater.start();
						App.camera_updaters['updater_' + camera.id] = updater;
					}
				}
					/*var updater = new CameraUpdater({element: element, url: camera.image_url});
					updater.init();
					updater.start();
					App.camera_updaters['updater_' + camera.id] = updater;*/

			}
		});
	},

	/*getCamera: function(camera_id) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', {id: camera_id }, function(data) {
			dfd.resolve(data);
		});
		return dfd;
	},*/



	deleteCamera: function(camera_id) {
		if(confirm('Are you sure you want to delete this camera?')) {
			$.ajax({
				url: App.API_URL + 'cameras.php?camera_id=' + camera_id,
				data: {id: camera_id },
				type: 'DELETE',
				success: function(result) {
					console.log(result);
					App.UI.Settings.showCameras();
				}
			}).fail(function(error) {
				console.log(error);
			});
		}
	},

	changePage: function(page_name) {
		App.showGlobalError(null, null, false);
		//kill any camera stream connections that are open
		$('.app-page.active-page .camera-stream').each(function(index, value) {
				$(value).attr('src', '');
		});

		$('.app-page').removeClass('active-page').hide();
		$('#' + page_name).show().removeClass('animated slideOutLeft').addClass('animated slideInRight active-page');
		if($('#main_nav').is(':visible')) {
			$('#main_nav').collapse('hide');
		}

	},

	setActiveNav: function(nav_name) {
		$('.navbar .navbar-nav li').removeClass('active');
		$('#' + nav_name).parent('li').addClass('active');
	},

	testConfiguration: function() {
		var base_url = this.getConfiguredCameraUrl();
		var image_url = $('#edit_camera_image').val();
		var url = base_url + image_url;
		if($('#edit_proxy_data').is(':checked')) {
			console.log('proxy');
			url = 'api/image_proxy.php?configuration_test=true&test_url=' + escape(url);
		}
		$('#config_test_image').attr('src', url);
	},

	getConfiguredCameraUrl: function() {
		var url = '';
		//url += 'http://';
		var protocol = $('#configuration_form').find('#edit_camera_protocol').val();
		url += protocol + '://';

		var username = $('#edit_camera_username').val();
		if(username) {
			url += username + ':';
		}
		var password = $('#edit_camera_password').val();
		if(password) {
			url += password + '@';
		}
		var server = $('#edit_camera_ip').val();
		url += server;

		var port = $('#edit_camera_port').val();
		url += ':' + port + '/';

		console.log(url);

		return url;
	},

	configurationError: function(error) {
		//console.log(error);
		$('#configuration_test_output').show();
		$('#configuration_test_output .alert-success').hide();
		$('#configuration_test_output .alert-danger').show();
	},

	configurationSuccess: function() {
		//console.log('success');
		$('#configuration_test_output').show();
		$('#configuration_test_output .alert-success').show();
		$('#configuration_test_output .alert-danger').hide();
	},



	showGlobalError: function(message, reason, show) {
		if(show === false) {
			$('#global_error').hide();
		}
		else {
			var source = $('#global_error-template').html();
			var template = Handlebars.compile(source);
			var html = template({message: message, reason: reason});
			$('#global_error').html(html);
			console.log(message);
			$('#global_error').show();
		}
	}
};

App.UI = {
	showLogin: function() {
		/*if($('#login').is(':visible')) {
			return;
		}*/
		var source = $('#login-template').html();
		var template = Handlebars.compile(source);
		var html = template({});
		//console.log();
		$('#login').html(html);
		App.changePage('login');
		App.setActiveNav('settings_link');
		if($('#main_nav').is(':visible')) {
			$('#main_nav').collapse('hide');
		}
	},

	show404: function(error_data) {
		var source = $('#error_404-template').html();
		var template = Handlebars.compile(source);
		var html = template(error_data);
		$('#error_404').html(html);
		App.changePage('error_404');
		App.setActiveNav('settings_link');
	}
};



App.UI.FirstRun = {
	initialise: function() {

	},
	show: function() {
		var source = $('#firstrun-template').html();
		var template = Handlebars.compile(source);
		var html = template({});
		$('#firstrun').html(html);
		App.changePage('firstrun');
		$('#firstrun').on('click', '.check-btn', function(e) {
			App.UI.FirstRun.check();
		});
		//App.setActiveNav('settings_link');
	},
	check: function() {
		$('#firstrun').html('Running Checks...');
		App.UI.FirstRun.checkPrerequisites().then(function(data) {
			console.log(data);
			var source = $('#prerequisite_check-template').html();
			var template = Handlebars.compile(source);
			var html = template(data);
			$('#firstrun').html(html);
			$('#firstrun').on('click', '.try-again-btn', function(e) {
				App.UI.FirstRun.check();
			});
			//App.UI.
			/*$('#prerequisite_check').html(html);
			console.log(html);
			App.changePage('prerequisite_check');*/
		});

		//App.setActiveNav('settings_link');
	},
	checkPrerequisites: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'firstrun.php', {prerequisites_check: true }, function(data) {
			dfd.resolve(data);
		});
		return dfd;
	},
	userSettings: function() {
		var source = $('#user_settings-template').html();
		var template = Handlebars.compile(source);
		var html = template({});
		$('#firstrun_user_settings').html(html);
		$('#firstrun_user_settings .time_24').attr('checked','checked');
		App.changePage('firstrun_user_settings');
		App.setActiveNav('settings_link');
	}
};

App.API = {
	login: function(form_data) {
		console.log(form_data);
		var dfd = new jQuery.Deferred();
		$.post(App.API_URL + 'login.php', form_data, function(data) {
			dfd.resolve(data);
			App.current_user = data.user;
		}).fail(function(data) {
			if(data.responseJSON) {
				dfd.reject(data.responseJSON);
			}
			else {
				dfd.reject(data.responseText);
				alert(data.responseText);
			}
		});
		return dfd;
	},

	logout: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'login.php', {action: 'logout'}, function(data) {
			dfd.resolve(data);
			App.current_user = null;
		}).fail(function(data) {
			if(data.responseJSON) {
				dfd.reject(data.responseJSON);
			}
			else {
				dfd.reject(data.responseText);
				alert(data.responseText);
			}
		});
		return dfd;
	}
};




function CameraUpdater(params)  {
	this.element = params.element;
	this.url = params.url;
	this.init = function() {
		this.element.on('load', $.proxy(function() {
			this.tick();
		}, this));
		this.element.on('error', $.proxy(function() {
			console.log('could not load image');
			setTimeout($.proxy(function() {
				this.tick();
			}, this), 10000);
		}, this));
	},

	this.start = function() {
		this.element.attr('src', this.url);
	},

	this.tick = function() {
		setTimeout($.proxy(function() {
			if(this.element.is(':visible')) {
				var url = this.url;
				if(this.url.indexOf('?') != -1) {
					url = this.url + '&ts=' + new Date().getTime();
				}
				else {
					url = this.url + '?ts=' + new Date().getTime();
				}
				this.element.attr('src', url);
			}
			else {
				if(!$.contains(document.documentElement, this.element)) {
					console.log('element no longer in DOM');
					return;
				}
				else {
					console.log('element hidden');
					this.tick();
				}
			}
		}, this), 100);
	}
};


$( document ).ready(function() {

	//the following event handlers are to work around a chrome bug preventing
	//ajax requests after an mjpeg stream loads
	/*$( document ).ajaxSend(function() {
		App.ajax_requests++;
		$('.camera-stream').each(function(index, value) {
			if($(value).attr('src') != '' || !$(value).is(':visible')) {
				if(!$(value).data('stream-url')) {
					$(value).data('stream-url', $(value).attr('src'));
				}
				$(value).attr('src', '');
			}
			$(value).attr('src', $(value).data('snapshot-url'));
		});
	});

	$( document ).ajaxComplete(function() {
		App.ajax_requests--;
		if(App.ajax_requests == 0) {
			$('.camera-stream').each(function(index, value) {
				if($(value).is(':visible')) {
					$(value).attr('src', $(value).data('stream-url'));
				}
			});
		}
	});*/

	$.ajaxSetup({
		statusCode: {
			401: function() {
				App.UI.showLogin();
			}
		}
	});

	App.Router = new SimpleRouter();

	App.Router.addRoute({
		url: 'camera_list',
		navigate: function(params) {
			App.renderCameras();
		}
	});

	App.Router.addRoute({
		url: 'settings',
		navigate: function(params) {
			App.UI.Settings.showSettings();
		}
	});

	App.Router.addRoute({
		url: 'user_settings',
		navigate: function(params) {
			App.UI.Settings.userSettings();
		}
	});

	App.Router.addRoute({
		url: 'firstrun_user_settings',
		navigate: function(params) {
			App.UI.FirstRun.userSettings();
		}
	});

	App.Router.addRoute({
		url: 'edit_camera',
		navigate: function(params) {
			App.UI.Cameras.showAddCamera();
		}
	});

	App.Router.addRoute({
		url: 'edit_camera/:camera_id',
		navigate: function(params) {
			App.UI.Cameras.showAddCamera(params.camera_id);
		}
	});

	App.Router.addRoute({
		url: 'import_camera/:motion_id/:thread_number',
		navigate: function(params) {
			App.UI.Cameras.showAddCamera(null, params.motion_id, params.thread_number);
		}
	});

	App.Router.addRoute({
		url: 'add_camera',
		navigate: function(params) {
			App.UI.Cameras.showAddCamera();
		}
	});

	App.Router.addRoute({
		url: 'check',
		navigate: function(params) {
			App.UI.FirstRun.check();
		}
	});

	App.Router.addRoute({
		url: 'view_camera/:camera_id',
		navigate: function(params) {
			App.UI.Cameras.renderCamera(params.camera_id);
		}
	});

	App.Router.addRoute({
		url: 'add_motion_server',
		navigate: function(params) {
			App.UI.Settings.addMotionServer();
		}
	});

	App.Router.addRoute({
		url: 'edit_motion_server/:motion_id',
		navigate: function(params) {
			App.UI.Settings.editMotionServer(params.motion_id);
		}
	});

	App.Router.addRoute({
		url: 'motion_servers',
		navigate: function(params) {
			App.UI.Settings.showMotionServers();
		}
	});

	App.Router.addRoute({
		url: 'cameras',
		navigate: function(params) {
			App.UI.Settings.showCameras();
		}
	});

	App.Router.addRoute({
		url: 'import_motion_cameras/:motion_id',
		navigate: function(params) {
			App.UI.Settings.importMotionCameras(params.motion_id);
		}
	});

	App.Router.addRoute({
		url: 'view_event/:camera_id/:date/:event_id',
		navigate: function(params) {
			App.UI.Cameras.viewEvent(params);
		}
	});

	App.Router.addRoute({
		url: 'save_success/:camera_id',
		navigate: function(params) {
			params['is_new'] = false;
			App.UI.Cameras.showConfigurationSaveSuccess(params);
		}
	});

	App.Router.addRoute({
		url: 'create_success/:camera_id',
		navigate: function(params) {
			params['is_new'] = true;
			App.UI.Cameras.showConfigurationSaveSuccess(params);
		}
	});

	App.Router.addRoute({
		url: '',
		navigate: function(params) {
			if(FIRST_RUN) {
				App.UI.FirstRun.show();
			}
			else {
				App.renderCameras();
			}
		}
	});

	App.Router.add404Handler(function() {
		console.log('404');
		App.UI.show404({ message: "The requested page could not be found."});
	});

	var History = window.History;

	//get the user data
	App.API.Settings.getUserSettings().then(function(data) {
		App.current_user = data;
		App.Router.route();
	},
	function(error) {
		if(error.message) {
			App.showGlobalError("Could not connect to server", error.message, true);
		}
		else {
			App.showGlobalError("Could not connect to server", "An error occurred while trying to communicate with the server.", true);
		}
	});



	if ( !History.enabled ) { return false; }
    History.pushState({page: window.location.href},null, window.location.href);           // save initial state to browser history

	// Bind to StateChange Event
	History.Adapter.bind(window,'statechange',function() {
		var State = History.getState();
		App.Router.route();
	});

	$(document).on('click', 'a', function(e){

		var href = $(this).attr('href');
		if(!href) {
			return;
		}
		/*if(href.indexOf(BASE_URL) !== 0) {
			return;
		}*/
		e.preventDefault();

		//$('.app-page.active-page').addClass('animated slideOutLeft');
		History.pushState({page:href}, null, href);
	});


	$(document).on('submit', '.user-settings-form', function( event ) {
		event.preventDefault();

		$(event.target).find('.form-group').removeClass('has-error');
		$(event.target).find('.validation-message').html('').hide();

		App.API.Settings.saveUserSettings($(event.target).serialize()).then(function(data) {
			FIRST_RUN = false;
			History.pushState({page:'camera_list'}, null, 'camera_list');
		},
		function(error_data) {
			if(error_data.errors) {
				for(var i = 0; i < error_data.errors.length; i++) {
					var error = error_data.errors[i];
					if(error.type == 'global') {
						App.showGlobalError(error.message, error.reason);
					}
					else if(error.type == 'field') {
						var form_element = $('.user-' + error.name + '-field');

						form_element.addClass('has-error');
						form_element.find('.validation-message').html(error.message).show();
					}
				}
			}
		});
	});

	$(document).on('submit', '#login_form', function( event ) {
		event.preventDefault();
		$('#login_form').removeClass('has-error');
		$('#login_form').find('.validation-message').hide();
		App.API.login($(this).serialize()).then(function(data) {
			App.Router.route();
		},
		function(error) {
			$('#login_form').addClass('has-error');
			if(error.message) {
				$('#login_form').find('.validation-message').html(error.message).show();
			}
		});
	});

	$(document).on('click', '.logout-btn', function( event ) {
		event.preventDefault();
		App.API.logout().then(function(data) {
			App.Router.route();
		},
		function(error) {
			console.log(error);
		});
	});



	Handlebars.registerHelper('dateFormat', function(context, block) {
		if (window.moment) {
			var fmat = (App.current_user) ? App.current_user.date_format : 'DD/MM/YYYY';
			var f = block.hash.format || fmat;
			if(block.hash.date_type == 'timestamp') {
				return moment.unix(context).format(f);
			}
			else {
				return moment(Date(context)).format(f);
			}
		}
		else{
			return context;   //  moment plugin not available. return data as is.
		}
	});

	Handlebars.registerHelper('dateTimeFormat', function(context, block) {
		if (window.moment) {
			var fmat = (App.current_user) ? App.current_user.date_format + ' ' + App.current_user.time_format : 'DD/MM/YYYY HH:mm:ss';
			var f = block.hash.format || fmat;
			if(block.hash.date_type == 'timestamp') {
				return moment.unix(context).format(f);
			}
			else {
				return moment(Date(context)).format(f);
			}
		}
		else{
			return context;   //  moment plugin not available. return data as is.
		}
	});

	Handlebars.registerHelper('timeFormat', function(context, block) {
		if (window.moment) {
			var fmat = (App.current_user) ? App.current_user.time_format : 'HH:mm:ss';
			var f = block.hash.format || fmat;
			if(block.hash.date_type == 'timestamp') {
				return moment.unix(context).format(f);
			}
			else {
				return moment(Date(context)).format(f);
			}
		}
		else{
			return context;   //  moment plugin not available. return data as is.
		}
	});

});