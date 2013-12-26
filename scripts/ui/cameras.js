App.UI.Cameras = {
	showAddCamera: function(camera_id, motion_id, thread_number) {
		if(camera_id) {
			App.API.Cameras.getCamera(camera_id, true).then(function(data) {
				var camera = data.camera;
				camera['motion_servers'] = data.motion_servers;
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
						App.UI.Cameras.addCommandForm(command, i);
					}
				}
				App.changePage('add_camera');
				App.setActiveNav('settings_link');
			});
		}
		if(motion_id && thread_number !== null) {
			App.API.Motion.getThread(motion_id, thread_number).then(function(data) {
				var camera = data.thread;
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
						App.UI.Cameras.addCommandForm(command, i);
					}
				}
				$('#edit_proxy_data').attr('checked', 'checked');
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
	}
};

$( document ).ready(function() {
	$(document).on('click', '.add-command', function(e) {
		App.UI.Cameras.addCommandForm();
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
			App.showConfigurationSaveSuccess(data, is_new);
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
});