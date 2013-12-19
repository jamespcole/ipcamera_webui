var App = {
	API_URL: BASE_URL + 'api/',
	camera_updaters: [],

	getCameras: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', function(data) {
			dfd.resolve(data);
		});
		return dfd;
	},

	renderCameras: function() {

		App.getCameras().then(function(data) {
			var source   = $("#camera_list-template").html();
			var template = Handlebars.compile(source);
			var html    = template(data);
			$("#camera_list").html(html);
			App.changePage('camera_list');
			App.setActiveNav('cameras_link');
			App.camera_updaters = [];
			for(var i = 0; i < data.cameras.length; i++) {
				var camera = data.cameras[i];
				if(!App.camera_updaters['updater_' + camera.id]) {
					var element = $('#image_' + camera.id);
					var updater = new CameraUpdater({element: element, url: camera.image_url});
					updater.init();
					updater.start();
					App.camera_updaters['updater_' + camera.id] = updater;
				}
			}
		});
	},

	getCamera: function(camera_id) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', {id: camera_id }, function(data) {
			dfd.resolve(data);
		});
		return dfd;
	},

	renderCamera: function(camera_id) {
		App.getCamera(camera_id).then(function(data) {
			var source   = $("#view_camera-template").html();
			var template = Handlebars.compile(source);
			var html    = template(data.camera);
			$("#view_camera").html(html);
			App.changePage('view_camera');
			App.setActiveNav('cameras_link');
			App.camera_updaters = [];

			var camera = data.camera;
			if(!App.camera_updaters['updater_active_camera_image']) {
				var element = $('#active_camera_image');
				var updater = new CameraUpdater({element: element, url: camera.image_url});
				updater.init();
				updater.start();
				App.camera_updaters['active_camera_image'] = updater;
			}

		});
	},

	deleteCamera: function(camera_id) {
		if(confirm('Are you sure you want to delete this camera?')) {
			$.ajax({
				url: App.API_URL + 'cameras.php?camera_id=' + camera_id,
				data: {id: camera_id },
				type: 'DELETE',
				success: function(result) {
					console.log(result);
					App.UI.Settings.showSettings();
				}
			}).fail(function(error) {
				console.log(error);
			});
		}
	},

	showAddCamera: function(camera_id) {
		if(camera_id) {
			App.getCamera(camera_id).then(function(data) {
				var camera = data.camera;
				var source = $('#add_camera-template').html();
				var template = Handlebars.compile(source);
				var html = template(camera);
				$('#add_camera').html(html);
				if(camera.proxy_data === true) {
					$('#edit_proxy_data').attr('checked', 'checked');
				}
				if(camera.commands) {
					for(var i = 0; i < camera.commands.length; i++) {
						var command = camera.commands[i];
						App.addCommandForm(command, i);
					}
				}
				App.changePage('add_camera');
				App.setActiveNav('settings_link');
			});
		}
		else {
			var source = $('#add_camera-template').html();
			var template = Handlebars.compile(source);
			var html = template({port: 80});
			$('#add_camera').html(html);
			$('#edit_proxy_data').attr('checked', 'checked');
			App.changePage('add_camera');
			App.setActiveNav('settings_link');
		}
		//App.getCameras().then(function(data) {

		//});
	},

	addCommandForm: function(command, index) {
		var source = $('#command-template').html();
		var template = Handlebars.compile(source);
		if(!command) {
			command = {};
		}
		if(!index) {
			index = $('.command-form').length;
		}
		command['command_index'] = index;
		command['command_display_index'] = index + 1;

		var html = template(command);

		$('#command_list').append(html);
		var icon_select = $('#command_list .command-icon-select')[index];
		if(command.command_icon) {
			$(icon_select).val(command.command_icon);
		}
	},

	/*showSettings: function() {
		App.getCameras().then(function(cameras_data) {
			var source = $('#settings-template').html();
			var template = Handlebars.compile(source);
			var data = {
				cameras: cameras_data.cameras
			};
			var html = template(data);
			$('#settings').html(html);
			App.changePage('settings');
			App.setActiveNav('settings_link');
		});
	},*/

	changePage: function(page_name) {
		App.showGlobalError(null, null, false);
		$('.app-page').removeClass('active-page').hide();
		//$('.app-page:not(.active-page)').hide();
		//$('.app-page.active-page').removeClass('active-page').addClass('animated slideOutLeft').hide();
		/*$('.app-page:not(.active-page)').hide();
		$('.app-page.active-page').removeClass('active-page').slideUp(function() {
			$('#' + page_name).show().addClass('animated slideInRight active-page');
		});*/
		//$('.app-page').addClass('animated slideOutRight');
		//$('#' + page_name).show();
		$('#' + page_name).show().removeClass('animated slideOutLeft').addClass('animated slideInRight active-page');
		//History.pushState({}, page_name, page_name);
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
		url += 'http://';
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

	saveConfiguration: function(form_data) {
		App.showGlobalError(null, null, false);
		var camera_id = $('#edit_camera_id').val();
		var is_new = (camera_id) ? false : true;
		//hide the validation errors before trying to save
		$('#configuration_form .form-group').removeClass('has-error');
		$('#configuration_form .validation-message').html('').hide();

		$.post(App.API_URL + 'cameras.php', form_data, function(data) {
			//console.log('success', data);
			App.showConfigurationSaveSuccess(data, is_new);

		}).fail(function(data) {
			if(data.responseJSON) {
				var error_data = data.responseJSON;
				if(error_data.errors) {
					console.log(error_data.errors.length);
					for(var i = 0; i < error_data.errors.length; i++) {
						var error = error_data.errors[i];
						if(error.type == 'global') {
							App.showGlobalError(error.message, error.reason);
						}
						else if(error.type == 'field') {
							var form_element = $('.camera-' + error.name + '-field');

							form_element.addClass('has-error');
							form_element.find('.validation-message').html(error.message).show();
						}
					}
				}
			}
			else {
				alert(data.responseText);
			}
		});
	},

	showConfigurationSaveSuccess: function(camera, is_new) {
		camera['is_new'] = is_new;
		var source = $('#save_success-template').html();
		var template = Handlebars.compile(source);
		var html = template(camera);
		$('#save_success').html(html);
		App.changePage('save_success');
		App.setActiveNav('settings_link');
	},

	showGlobalError: function(message, reason, show) {
		if(show === false) {

		}
		else {
			var source = $('#global_error-template').html();
			var template = Handlebars.compile(source);
			var html = template({message: message, reason: reason});
			$('#global_error').html(html);
			console.log(message);
		}
	}
};

App.UI = {

};

App.UI.Settings = {
	initialise: function() {

	},

	showSettings: function() {
		App.getCameras().then(function(cameras_data) {
			var source = $('#settings-template').html();
			var template = Handlebars.compile(source);
			var data = {
				cameras: cameras_data.cameras
			};
			var html = template(data);
			$('#settings').html(html);
			App.changePage('settings');
			App.setActiveNav('settings_link');
		});
	},
	userSettings: function() {
		var source = $('#user_settings-template').html();
		var template = Handlebars.compile(source);
		var html = template({});
		$('#user_settings').html(html);
		App.changePage('user_settings');
		App.setActiveNav('settings_link');
	},

	addMotionServer: function() {
		var source = $('#edit_motion-template').html();
		var template = Handlebars.compile(source);
		var html = template({});
		$('#edit_motion').html(html);
		App.changePage('edit_motion');
		App.setActiveNav('settings_link');
	}

	/*,

	showConfigurationSaveSuccess: function(camera, is_new) {
		camera['is_new'] = is_new;
		var source = $('#save_success-template').html();
		var template = Handlebars.compile(source);
		var html = template(camera);
		$('#save_success').html(html);
		App.changePage('save_success');
		App.setActiveNav('settings_link');
	}*/
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
	saveUserSettings: function(form_data) {
		var dfd = new jQuery.Deferred();
		$.post(App.API_URL + 'settings.php', form_data, function(data) {
			dfd.resolve(data);
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

function Router()  {
	this.init = function() {

	},

	this.route = function() {
		var url_data = window.location.href.replace(BASE_URL, '');
		//console.log(url_data);
		var bits = url_data.split('/');
		//console.log(bits);
		if(bits.length == 0 || bits[0] == '' || bits[0] == '/') {
			if(FIRST_RUN) {
				App.UI.FirstRun.show();
			}
			else {
				App.renderCameras();
			}
		}
		else {
			var page = bits[0];

			if(page == 'camera_list') {
				App.renderCameras();
			}
			else if(page == 'settings') {
				App.UI.Settings.showSettings();
			}
			else if(page == 'user_settings') {
				App.UI.Settings.userSettings();
			}
			else if(page == 'firstrun_user_settings') {
				App.UI.FirstRun.userSettings();
			}
			else if(page == 'edit_camera') {
				if(bits.length > 1) {
					App.showAddCamera(bits[1]);
				}
				else {
					App.showAddCamera();
				}
			}
			else if(page == 'add_camera') {
				App.showAddCamera();
			}
			else if(page == 'check') {
				App.UI.FirstRun.check();
			}
			else if(page == 'view_camera') {
				if(bits.length > 1) {
					App.renderCamera(bits[1]);
				}
				else {

				}

			}
			else if(page == 'add_motion_server') {
				App.UI.Settings.addMotionServer();
			}
			else {
				App.renderCameras();
			}
		}
	}
}

$( document ).ready(function() {
	App.Router = new Router();
	var History = window.History;

	App.Router.route();

	if ( !History.enabled ) { return false; }
    History.pushState({page: window.location.href},null, window.location.href);           // save initial state to browser history

	// Bind to StateChange Event
	History.Adapter.bind(window,'statechange',function() {
		var State = History.getState();
		App.Router.route();
	});

	$(document).on('click', '.delete-camera-link', function(e) {
		var camera_id = $(this).data('camera-id');
		App.deleteCamera(camera_id);
	});

	$(document).on('click', '.camera-command', function(e) {
		var c_url = $(this).data('command-url');
		$.get(App.API_URL + 'command_proxy.php', {command_url: c_url});
	});
	$(document).on('click', '#test_config_btn', function(e) {
		App.testConfiguration();
	});

	$(document).on('click', '.add-command', function(e) {
		App.addCommandForm();
	});

	$(document).on('click', 'a', function(e){

		var href = $(this).attr('href');
		if(!href) {
			return;
		}
		if(href.indexOf(BASE_URL) !== 0) {
			return;
		}
		e.preventDefault();

		$('.app-page.active-page').addClass('animated slideOutLeft');
		History.pushState({page:href}, null, href);
	});

	$(document).on('submit', '#configuration_form', function( event ) {
		event.preventDefault();
		var url = App.getConfiguredCameraUrl();
		console.log('saving', url);
		$('#edit_image_url').val(App.getConfiguredCameraUrl() + $('#edit_camera_image').val());
		$('#edit_camera_base_url').val(url);

		App.saveConfiguration($(this).serialize());
	});

	$(document).on('submit', '.user-settings-form', function( event ) {
		event.preventDefault();
		var url = App.getConfiguredCameraUrl();
		//console.log(event.target);
		//console.log('saving', url);
		//$('#edit_image_url').val(App.getConfiguredCameraUrl() + $('#edit_camera_image').val());
		//$('#edit_camera_base_url').val(url);

		console.log($(event.target).serialize());
		$(event.target).find('.form-group').removeClass('has-error');
		$(event.target).find('.validation-message').html('').hide();

		App.API.saveUserSettings($(event.target).serialize()).then(function(data) {
			FIRST_RUN = false;
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

});