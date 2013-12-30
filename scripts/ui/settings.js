App.UI.Settings = {
	initialise: function() {

	},

	showSettings: function() {
		//App.API.Cameras.getCameras().then(function(cameras_data) {
			var source = $('#settings-template').html();
			var template = Handlebars.compile(source);

			var html = template({});
			$('#settings').html(html);
			App.changePage('settings');
			App.setActiveNav('settings_link');
		//});
	},
	userSettings: function() {
		if(FIRST_RUN === true) {
			var source = $('#user_settings-template').html();
			var template = Handlebars.compile(source);
			var html = template({});
			$('#user_settings').html(html);
			$('#user_settings .time_24').attr('checked','checked');
			App.changePage('user_settings');
			App.setActiveNav('settings_link');
		}
		else {
			App.API.Settings.getUserSettings().then(function(data) {
				var source = $('#user_settings-template').html();
				var template = Handlebars.compile(source);
				var html = template(data);
				$('#user_settings').html(html);
				if(data.time_format == 'HH:mm') {
					$('#user_settings .time_24').attr('checked','checked');
				}
				else {
					$('#user_settings .time_12').attr('checked','checked');
				}
				$('#user_settings .date-format-select').val(data.date_format);

				App.changePage('user_settings');
				App.setActiveNav('settings_link');
			},
			function(error) {
				App.changePage('user_settings');
				App.setActiveNav('settings_link');
				if(error.message) {
					App.showGlobalError("Could not connect to server", error.message, true);
				}
				else {
					App.showGlobalError("Could not connect to server", "An error occurred while trying to communicate with the server.", true);
				}
				$('#user_settings').html('');


			});
		}

	},

	addMotionServer: function() {
		var source = $('#edit_motion-template').html();
		var template = Handlebars.compile(source);
		var html = template({port: '8080', server: '127.0.0.1', 'config_file': '/etc/motion/motion.conf'});
		$('#edit_motion').html(html);
		App.changePage('edit_motion');
		App.setActiveNav('settings_link');
	},

	editMotionServer: function(motion_id) {
		App.API.Motion.getMotionServer(motion_id).then(function(data) {
			var source = $('#edit_motion-template').html();
			var template = Handlebars.compile(source);
			var html = template(data);
			$('#edit_motion').html(html);
			App.changePage('edit_motion');
			App.setActiveNav('settings_link');
		},
		function(error) {
			App.changePage('edit_motion');
			App.setActiveNav('settings_link');
			if(error.message) {
				App.showGlobalError("Could not connect to server", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to server", "An error occurred while trying to communicate with the server.", true);
			}
			$('#edit_motion').html('');


		});
	},

	testMotionSettings: function() {
		var url = App.UI.Settings.getConfiguredMotionUrl();
		App.API.Motion.testServer(url).then(function(data) {
			$('#edit_motion .configuration_test_output').show();
			$('#edit_motion .configuration_test_output .alert-success').show();
			$('#edit_motion .configuration_test_output .alert-danger').hide();
		}, function(error) {
			$('#edit_motion .configuration_test_output .alert-danger .error-message').html(error.message);
			$('#edit_motion .configuration_test_output').show();
			$('#edit_motion .configuration_test_output .alert-success').hide();
			$('#edit_motion .configuration_test_output .alert-danger').show();
		});
	},

	getConfiguredMotionUrl: function() {
		var server = $('#edit_motion').find('#edit_motion_ip').val();
		var port = $('#edit_motion').find('#edit_motion_port').val();
		var username = $('#edit_motion').find('#edit_motion_username').val();
		var password = $('#edit_motion').find('#edit_motion_password').val();
		var protocol = $('#edit_motion').find('#edit_motion_protocol').val();
		var url = protocol + '://';
		if(username != '' && password != '') {
			url += username + ':' + password + '@';
		}
		url += server + ':' + port;
		//console.log(url);
		return url;
	},

	importMotionCameras: function(motion_id) {
		App.API.Motion.getThreads(motion_id).then(function(data) {
			var source = $('#import_motion_cameras-template').html();
			var template = Handlebars.compile(source);
			data['motion_id'] = motion_id;
			//console.log(data);
			var html = template(data);
			$('#import_motion_cameras').html(html);
			App.changePage('import_motion_cameras');
			App.setActiveNav('settings_link');
		},
		function(error) {
			App.changePage('import_motion_cameras');
			App.setActiveNav('settings_link');
			if(error.message) {
				App.showGlobalError("Could not connect to motion server", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to motion server", "An error occurred while trying to communicate with the motion server.", true);
			}
			$('#import_motion_cameras').html('');
		});
	},

	showMotionServers: function() {
		App.API.Motion.getMotionServers().then(function(data) {
			if(data.motion_servers.length === 0) {
				History.pushState({page:'add_motion_server'}, null, 'add_motion_server');
			}
			else {
				var source = $('#motion_servers-template').html();
				var template = Handlebars.compile(source);
				var html = template(data);
				$('#motion_servers').html(html);
				App.changePage('motion_servers');
				App.setActiveNav('settings_link');
			}
		},
		function(error) {
			App.changePage('motion_servers');
			App.setActiveNav('settings_link');
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}
			$('#motion_servers').html('');

		});
	},

	showCameras: function() {
		App.API.Cameras.getCameras().then(function(data) {
			var source = $('#cameras-template').html();
			var template = Handlebars.compile(source);
			var html = template(data);
			$('#cameras').html(html);
			App.changePage('cameras');
			App.setActiveNav('settings_link');
		},
		function(error) {
			App.changePage('cameras');
			App.setActiveNav('settings_link');
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}
			$('#cameras').html('');

		});
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


$( document ).ready(function() {

	$(document).on('click', '#edit_motion .test_config_btn', function( event ) {
		App.UI.Settings.testMotionSettings();
	});

	$(document).on('click', '.delete-motion-server-link', function(e) {
		var motion_id = $(this).data('motion-id');
		if(confirm('Are you sure you want to delete this motion server?')) {
			App.API.Motion.deleteMotionServer(motion_id).then(function(data) {
				App.UI.Settings.showMotionServers();
			},
			function(error) {
				console.log(error);
			});
		}
	});

	$(document).on('submit', '.edit_motion_form', function( event ) {
		event.preventDefault();
		var url = App.UI.Settings.getConfiguredMotionUrl();
		$('.edit_motion_form #edit_motion_url').val(url);
		//hide the validation errors before trying to save
		$('.edit_motion_form .form-group').removeClass('has-error');
		$('.edit_motion_form .validation-message').html('').hide();
		App.API.Motion.saveConfigurationSettings($(this).serialize()).then(function(data) {
			if($('.edit_motion_form #edit_motion_id').val()) {
				History.pushState({page:'motion_servers'}, null, 'motion_servers');
			}
			else {
				History.pushState({page:'import_motion_cameras/' + data.id}, null, 'import_motion_cameras/' + data.id);
			}
		},
		function(error_data) {
			if(error_data.errors) {
				for(var i = 0; i < error_data.errors.length; i++) {
					var error = error_data.errors[i];
					if(error.type == 'global') {
						App.showGlobalError(error.message, error.reason);
					}
					else if(error.type == 'field') {
						var form_element = $('.motion-' + error.name + '-field');

						form_element.addClass('has-error');
						form_element.find('.validation-message').html(error.message).show();
					}
				}
			}
		});
	});

	$(document).on('click', '.try-again-motion-btn', function(e) {
		App.UI.Settings.importMotionCameras($(e.target).data('motion-id'));
	});


});