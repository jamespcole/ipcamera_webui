App.UI.CameraSettings = {
	current_camera:null,
	default_status_handler: 'function(element, status_data, command, camera_info) {\n\t\n}',
	default_before_command_handler: 'function(element, command, camera_info) {\n\t\n}',
	default_after_command_handler: 'function(element, command, camera_info, command_result) {\n\t\n}',
	default_status_parser: 'function(data, camera_info) {\n\t\n}',
	showAddCamera: function(camera_id, motion_id, thread_number) {
		App.API.Cameras.getCameraModels({}).then(function(camera_models) {
			if(camera_id) {
				App.API.Cameras.getCamera(camera_id, true).then(function(data) {
					var camera = data.camera;
					camera['motion_servers'] = data.motion_servers;
					camera['camera_models'] = App.camera_models;
					var source = $('#add_camera-template').html();
					var template = Handlebars.compile(source);
					var html = template(camera);
					$('#add_camera').html(html);

					$('#edit_camera_protocol').val(data.camera.protocol);
					$('#edit_camera_motion_id').val(data.camera.motion_id);
					$('#edit_camera_model_id').val(data.camera.model_id);

					if(camera.proxy_data === true) {
						$('#edit_proxy_data').attr('checked', 'checked');
					}
					var show_advanced = false;
					if(camera.commands) {
						show_advanced = true;
						for(var i = 0; i < camera.commands.length; i++) {
							var command = camera.commands[i];
							App.UI.CameraSettings.addCommandForm(command, i);
						}
					}
					if(camera.status_handlers) {
						show_advanced = true;
						for(var i = 0; i < camera.status_handlers.length; i++) {
							var status_handler = camera.status_handlers[i];
							App.UI.CameraSettings.addStatusHandlerForm(status_handler, i);
						}
					}
					if(show_advanced) {
						$('#edit_show_advanced').attr('checked', 'checked').closest('.camera-settings').addClass('advanced-camera-options');
					}
					$('#edit_show_advanced').on('change', function(e) {
						if($(this).is(':checked')) {
							$(this).closest('.camera-settings').addClass('advanced-camera-options');
						}
						else {
							$(this).closest('.camera-settings').removeClass('advanced-camera-options');
						}
					});

					App.changePage('add_camera');
					App.setActiveNav('settings_link');
				});
			}
			else if(motion_id && thread_number !== null) {
				App.API.Motion.getThread(motion_id, thread_number).then(function(data) {
					var camera = data.thread;
					camera['camera_models'] = App.camera_models;
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
							App.UI.CameraSettings.addCommandForm(command, i);
						}
					}
					if(camera.status_handlers) {
						for(var i = 0; i < camera.status_handlers.length; i++) {
							var status_handler = camera.status_handlers[i];
							App.UI.CameraSettings.addStatusHandlerForm(status_handler, i);
						}
					}
					$('#edit_proxy_data').attr('checked', 'checked');
					App.changePage('add_camera');
					App.setActiveNav('settings_link');

					$('#edit_show_advanced').on('change', function(e) {
						if($(this).is(':checked')) {
							$(this).closest('.camera-settings').addClass('advanced-camera-options');
						}
						else {
							$(this).closest('.camera-settings').removeClass('advanced-camera-options');
						}
					});
				});
			}
			else {
				App.API.Motion.getMotionServers().then(function(data) {
					data['port'] = 80;
					data['camera_models'] = App.camera_models;
					var source = $('#add_camera-template').html();
					var template = Handlebars.compile(source);
					var html = template(data);
					$('#add_camera').html(html);
					$('#edit_proxy_data').attr('checked', 'checked');
					App.changePage('add_camera');
					App.setActiveNav('settings_link');

					$('#edit_show_advanced').on('change', function(e) {
						if($(this).is(':checked')) {
							$(this).closest('.camera-settings').addClass('advanced-camera-options');
						}
						else {
							$(this).closest('.camera-settings').removeClass('advanced-camera-options');
						}
					});
				},
				function(error) {
					if(error.message) {
						App.showGlobalError("Could not connect to API", error.message, true);
					}
					else {
						App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
					}

				});

			}
		}, function (error) {
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}
		});

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

		if(!command.status_handler) {
			command.status_handler = App.UI.CameraSettings.default_status_handler;
		}

		if(!command.before_command_handler) {
			command.before_command_handler = App.UI.CameraSettings.default_before_command_handler;
		}

		if(!command.after_command_handler) {
			command.after_command_handler = App.UI.CameraSettings.default_after_command_handler;
		}

		var html = template(command);
		var inserted_form = $('#command_list').append(html).find('.command-form').last();

		inserted_form.find('.remove-command-btn').on('click', function(e) {
			if(confirm('Dow you really want to remove this command?')) {
				$(this).closest('.command-form').fadeOut({
					duration: 'slow',
					complete: function(e) {
						$(this).remove();
					}
				});
			}
		});

		var icon_select = $('#command_list .command-icon-select')[index];
		if(command.command_icon) {
			$(icon_select).val(command.command_icon);
		}


	},

	addStatusHandlerForm: function(status_handler, index) {
		var source = $('#status_handler-template').html();
		var template = Handlebars.compile(source);
		if(!status_handler) {
			status_handler = {};
		}
		var html = template(status_handler);
		$('#status_handler_list').append(html);
		$('#status_handler_list .test-handler-btn').on('click', function(event) {
			var target = this;
			var base_url = App.getConfiguredCameraUrl();
			var image_url = $(this).closest('.status-handler-form').find('.status-url').val();
			var url = base_url + image_url;
			var status_parser = $(this).closest('.status-handler-form').find('.status-parser').val();
			App.API.Cameras.getTestStatusData({test_status_url: url}).then(function(data) {
				try {
					var parser = eval('var fn = ' + status_parser);
					var result = fn(data.result);
					$(target).closest('.status-handler-form').find('.status-parser-result').val(JSON.stringify(result, null, "\t")).closest('.form-group').show();
				}
				catch(ex) {
					console.log(JSON.stringify(ex));
				}
			}, function(error) {
				console.log(error);
			});



		});
	},


	showConfigurationSaveSuccess: function(params) {
		App.API.Cameras.getCamera(params.camera_id).then(function(data) {
			var camera = data.camera;
			camera['is_new'] = params.is_new;
			var source = $('#save_success-template').html();
			var template = Handlebars.compile(source);
			var html = template(camera);
			$('#save_success').html(html);
			App.changePage('save_success');
			App.setActiveNav('settings_link');
		},
		function(error) {
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}

		});
	},

	//this is a hack to get around the load event on images failing
	//when the image is loaded into a handlebars template
	imageLoaded: function(target) {
		$(target).parent('.image-loading').removeClass('image-loading').addClass('image-loaded');
	}


};

$( document ).ready(function() {
	$(document).on('click', '.add-command', function(e) {
		App.UI.CameraSettings.addCommandForm();
	});

	$(document).on('click', '.delete-camera-link', function(e) {
		var camera_id = $(this).data('camera-id');
		App.deleteCamera(camera_id);
	});

	$(document).on('click', '#test_config_btn', function(e) {
		App.testConfiguration();
	});

	$(document).on('submit', '#configuration_form', function( event ) {
		event.preventDefault();
		var url = App.getConfiguredCameraUrl();

		$('#edit_image_url').val(App.getConfiguredCameraUrl() + $('#edit_camera_image').val());
		$('#edit_camera_base_url').val(url);

		//hide the validation errors before trying to save
		$('#configuration_form .form-group').removeClass('has-error');
		$('#configuration_form .validation-message').html('').hide();
		App.showGlobalError(null, null, false);

		App.API.Cameras.saveConfiguration($(this).serialize()).then(function(data) {
			var camera_id = $('#edit_camera_id').val();
			var is_new = (camera_id) ? false : true;
			console.log(data);
			//App.showConfigurationSaveSuccess(data, is_new);
			if(is_new) {
				History.pushState({page:'create_success/' + data.camera_id}, null, 'create_success/' + data.id);
			}
			else {
				History.pushState({page:'save_success/' + data.camera_id}, null, 'save_success/' + data.id);
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
						var form_element = $('.camera-' + error.name + '-field');

						form_element.addClass('has-error');
						form_element.find('.validation-message').html(error.message).show();
					}
				}
			}
		});
	});


	$(document).on('change', '#edit_camera_model_id', function(event) {

		var model_index = $(this).find(":selected").data('index');
		var camera_id = $('#edit_camera_id').val();
		if(model_index != -1) {
			var show_advanced = false;
			var model_data = App.camera_models.cameras[model_index];
			$('#edit_camera_image').val(model_data.image_url);
			if(!camera_id) {
				$('#command_list').html('');
				$('#status_handler_list').html('');
			}
			if(model_data.commands) {
				show_advanced = true;
				$('#command_list').html('');
				for(var i = 0; i < model_data.commands.length; i++) {
					var command = model_data.commands[i];
					App.UI.CameraSettings.addCommandForm(command);
				}
			}

			if(model_data.status_handlers) {
				show_advanced = true;
				$('#status_handler_list').html('');
				for(var i = 0; i < model_data.status_handlers.length; i++) {
					var status_handler = model_data.status_handlers[i];
					App.UI.CameraSettings.addStatusHandlerForm(status_handler);
				}
			}

			if(show_advanced) {
				if(show_advanced) {
					$('#edit_show_advanced').attr('checked', 'checked').closest('.camera-settings').addClass('advanced-camera-options');
				}
			}
			else {
				$('#edit_show_advanced').removeAttr('checked').closest('.camera-settings').removeClass('advanced-camera-options');
			}
		}
	});

	$(document).on('keydown', '.accepts_tab', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode == 9) {
			e.preventDefault();
			var start = $(this).get(0).selectionStart;
			var end = $(this).get(0).selectionEnd;

			// set textarea value to: text before caret + tab + text after caret
			$(this).val($(this).val().substring(0, start)
			+ "\t"
			+ $(this).val().substring(end));

			// put caret at right position again
			$(this).get(0).selectionStart =
			$(this).get(0).selectionEnd = start + 1;
		}
	});

	/*$(document).on('load', '.image-loading', function( event ) {
		console.log('loaded');
	});*/

});