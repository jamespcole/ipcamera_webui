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
					if(camera.commands && camera.commands.length > 0) {
						show_advanced = true;
						for(var i = 0; i < camera.commands.length; i++) {
							var command = camera.commands[i];
							//App.UI.CameraSettings.addCommandForm(command, i);
							App.UI.CameraSettings.addCommandRow(command);
						}
					}
					if(camera.status_handlers && camera.status_handlers.length > 0) {
						show_advanced = true;
						for(var i = 0; i < camera.status_handlers.length; i++) {
							var status_handler = camera.status_handlers[i];
							//App.UI.CameraSettings.addStatusHandlerForm(status_handler, i);
							App.UI.CameraSettings.addStatusParserRow(status_handler);
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
					$('.sortable-table').sortable({
						containerSelector: 'table',
						itemPath: '> tbody',
						itemSelector: 'tr',
						placeholder: '<tr class="placeholder"/>'
					});
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
					if(camera.commands && camera.commands.length > 0) {
						for(var i = 0; i < camera.commands.length; i++) {
							var command = camera.commands[i];
							//App.UI.CameraSettings.addCommandForm(command, i);
							App.UI.CameraSettings.addCommandRow(command);
						}
					}
					if(camera.status_handlers && camera.status_handlers.length > 0) {
						for(var i = 0; i < camera.status_handlers.length; i++) {
							var status_handler = camera.status_handlers[i];
							//App.UI.CameraSettings.addStatusHandlerForm(status_handler, i);
							App.UI.CameraSettings.addStatusParserRow(status_handler);
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
					$('.sortable-table').sortable({
						containerSelector: 'table',
						itemPath: '> tbody',
						itemSelector: 'tr',
						placeholder: '<tr class="placeholder"/>'
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
					$('.sortable-table').sortable({
						containerSelector: 'table',
						itemPath: '> tbody',
						itemSelector: 'tr',
						placeholder: '<tr class="placeholder"/>'
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

	/*addCommandForm: function(command, index) {
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


	},*/

	addCommandForm: function(command, index) {
		$('#edit_command_modal').modal('show');
		var source = $('#command-template').html();
		var template = Handlebars.compile(source);
		if(!command) {
			command = {};
		}
		if(!index) {
			//index = $('.command-form').length;
			index = 0;
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
		$('#edit_command_modal .modal-body .edit-command-form').html(html);

		var icon_select = $('#edit_command_modal .modal-body .command-icon-select');
		if(command.command_icon) {
			$(icon_select).val(command.command_icon);
		}
		var type_select = $('#edit_command_modal .modal-body .command-type-select');
		if(command.command_type) {
			$(type_select).val(command.command_type);
		}
		else {
			$(type_select).val('button');
		}
		$('#edit_command_modal .modal-dialog').addClass('command-type-' + $(type_select).val());

		type_select.on('change', function(e) {
			$('#edit_command_modal .modal-dialog').removeClass('command-type-text command-type-button command-type-group command-type-placeholder');
			$('#edit_command_modal .modal-dialog').addClass('command-type-' + $(this).val());
		});

		$('#edit_command_modal .modal-dialog').removeClass('command-type-text command-type-button command-type-group command-type-placeholder');
		$('#edit_command_modal .modal-dialog').addClass('command-type-' + $(type_select).val());

		var group_type_select = $('#edit_command_modal .modal-body .command-group-type-select');
		if(command.group_type) {
			$(group_type_select).val(command.group_type);
		}
		else {
			$(group_type_select).val('normal');
		}

		var control_size_select = $('#edit_command_modal .modal-body .command-control-size-select');
		if(command.control_size) {
			$(control_size_select).val(command.control_size);
		}
		else {
			$(control_size_select).val('');
		}

	},

	addCommandRow: function(command, index) {
		var source = $('#command-row-template').html();
		var template = Handlebars.compile(source);

		var html = template(command);

		if(index && index !== 0) {
			var row = $('#commands_table tbody tr')[index];
			$(row).replaceWith(html);
		}
		else {
			$('#commands_table tbody').append(html);
		}
	},

	addStatusParserRow: function(status_parser, index) {
		var source = $('#status-parser-row-template').html();
		var template = Handlebars.compile(source);

		var html = template(status_parser);

		if(index && index !== 0) {
			var row = $('#status_parsers_table tbody tr')[index];
			$(row).replaceWith(html);
		}
		else {
			$('#status_parsers_table tbody').append(html);
		}
	},


	addStatusParserForm: function(status_parser, index) {
		$('#edit_status_parser_modal').modal('show');
		var source = $('#status_parser-template').html();
		var template = Handlebars.compile(source);
		if(!status_parser) {
			status_parser = {};
		}
		if(!status_parser.status_parser) {
			status_parser.status_parser = App.UI.CameraSettings.default_status_parser;
		}
		var html = template(status_parser);
		$('#edit_status_parser_modal .modal-body .edit-status-parser-form').html(html);

		$('#edit_status_parser_modal .test-parser-btn').on('click', function(event) {
			var target = this;
			var base_url = App.getConfiguredCameraUrl();
			var image_url = $(this).closest('.status-parser-form').find('.status-url').val();
			var url = base_url + image_url;
			var status_parser = $(this).closest('.status-parser-form').find('.status-parser').val();
			App.API.Cameras.getTestStatusData({test_status_url: url}).then(function(data) {
				try {
					var parser = eval('var fn = ' + status_parser);
					var result = fn(data.result);
					$(target).closest('.status-parser-form').find('.status-parser-result').val(JSON.stringify(result, null, "\t")).closest('.form-group').show();
				}
				catch(ex) {
					console.log(JSON.stringify(ex));
					$(target).closest('.status-parser-form').find('.status-parser-result').val(ex).closest('.form-group').show();
				}
			}, function(error) {
				console.log(error);
				$(target).closest('.status-parser-form').find('.status-parser-result').val(error).closest('.form-group').show();
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
		//App.UI.CameraSettings.addCommandForm();
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
				$('#commands_table tbody').html('');
				$('#status_handler_list').html('');
			}
			if(model_data.commands) {
				show_advanced = true;
				$('#commands_table tbody').html('');
				for(var i = 0; i < model_data.commands.length; i++) {
					var command = model_data.commands[i];
					App.UI.CameraSettings.addCommandRow(command);
				}
			}

			if(model_data.status_handlers) {
				show_advanced = true;
				$('#status_handler_list').html('');
				for(var i = 0; i < model_data.status_handlers.length; i++) {
					var status_handler = model_data.status_handlers[i];
					//App.UI.CameraSettings.addStatusHandlerForm(status_handler);
					App.UI.CameraSettings.addStatusParserRow(status_handler);
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

	$(document).on('click', '.show-command-edit-modal', function(e) {

		var parent_row = $(this).parents('tr');
		var index = parent_row.parent().find('tr').index(parent_row);
		var command = {};
		command.row_index = index;
		command.button_text = parent_row.find('.command-form-fields .button_text').val();
		command.command_icon = parent_row.find('.command-form-fields .command_icon').val();
		command.command_url = parent_row.find('.command-form-fields .command_url').val();
		command.status_handler = parent_row.find('.command-form-fields .status_handler').val();
		command.before_command_handler = parent_row.find('.command-form-fields .before_command_handler').val();
		command.after_command_handler = parent_row.find('.command-form-fields .after_command_handler').val();
		command.command_type = parent_row.find('.command-form-fields .command_type').val();
		command.group_type = parent_row.find('.command-form-fields .group_type').val();
		command.command_name = parent_row.find('.command-form-fields .command_name').val();
		command.control_size = parent_row.find('.command-form-fields .control_size').val();
		//var command = App.UI.CameraSettings.current_camera.commands[i];
		App.UI.CameraSettings.addCommandForm(command);
	});

	$(document).on('click', '.save-command-edit-modal', function(e) {
		var index = $('#edit_command_modal .row_index').val();
		var parent_row = $('#commands_table').find('tr')[index];
		var fields = $(parent_row).find('.command-form-fields');

		var form_fields = $('#edit_command_modal .command-form');
		var command = {};
		command.row_index = index;
		command.button_text = form_fields.find('.button_text').val();
		command.command_icon = form_fields.find('.command_icon').val();
		command.command_url = form_fields.find('.command_url').val();
		command.status_handler = form_fields.find('.status_handler').val();
		command.before_command_handler = form_fields.find('.before_command_handler').val();
		command.after_command_handler = form_fields.find('.after_command_handler').val();
		command.command_type = form_fields.find('.command_type').val();
		command.group_type = form_fields.find('.group_type').val();
		command.command_name = form_fields.find('.command_name').val();
		command.control_size = form_fields.find('.control_size').val();


		//var command = {};
		/*fields.find('.button_text').val(command.button_text);
		fields.find('.command_icon').val(command.command_icon);
		fields.find('.command_url').val(command.command_url);
		fields.find('.status_handler').val(command.status_handler);
		fields.find('.before_command_handler').val(command.before_command_handler);
		fields.find('.after_command_handler').val(command.after_command_handler);*/
		App.UI.CameraSettings.addCommandRow(command, index);

		$('#edit_command_modal').modal('hide');
		//var command = App.UI.CameraSettings.current_camera.commands[i];
		//App.UI.CameraSettings.addCommandForm2(command);
	});

	$(document).on('click', '.delete-command-btn', function(e) {
		if(confirm('Do you really want to remove this command?')) {
			$(this).parents('tr').fadeOut({
				duration: 'slow',
				complete: function(e) {
					$(this).remove();
				}
			});
		}
	});

	$(document).on('click', '.show-status-parser-edit-modal', function(e) {

		var parent_row = $(this).parents('tr');
		var index = parent_row.parent().find('tr').index(parent_row);
		var fields = $(parent_row).find('.status-parser-form-fields');
		var status_parser = {};
		status_parser.row_index = index;
		status_parser.status_url = fields.find('.status_url').val();
		status_parser.status_parser = fields.find('.status_parser').val();

		//var command = App.UI.CameraSettings.current_camera.commands[i];
		App.UI.CameraSettings.addStatusParserForm(status_parser);
	});

	$(document).on('click', '.save-status-parser-edit-modal', function(e) {
		var index = $('#edit_status_parser_modal .row_index').val();
		var parent_row = $('#status_parsers_table').find('tr')[index];
		var fields = $(parent_row).find('.status-parser-form-fields');

		var form_fields = $('#edit_status_parser_modal .status-parser-form');
		var status_parser = {};
		status_parser.row_index = index;
		status_parser.status_url = form_fields.find('.status_url').val();
		status_parser.status_parser = form_fields.find('.status_parser').val();

		App.UI.CameraSettings.addStatusParserRow(status_parser, index);

		$('#edit_status_parser_modal').modal('hide');
	});

	$(document).on('click', '.delete-status-parser-btn', function(e) {
		if(confirm('Do you really want to remove this status parser?')) {
			$(this).parents('tr').fadeOut({
				duration: 'slow',
				complete: function(e) {
					$(this).remove();
				}
			});
		}
	});

	$(document).on('click', '.add-status-parser', function(e) {
		App.UI.CameraSettings.addStatusParserForm();
	});
	/*$(document).on('load', '.image-loading', function( event ) {
		console.log('loaded');
	});*/

});