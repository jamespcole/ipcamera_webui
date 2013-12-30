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

				$('#edit_camera_protocol').val(data.camera.protocol);

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
			App.API.Motion.getMotionServers().then(function(data) {
				data['port'] = 80;
				var source = $('#add_camera-template').html();
				var template = Handlebars.compile(source);
				var html = template(data);
				$('#add_camera').html(html);
				$('#edit_proxy_data').attr('checked', 'checked');
				App.changePage('add_camera');
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
	},

	getHistory: function(params) {
		App.API.Cameras.getHistory(params).then(function(history) {
			var source = $('#history-template').html();
			var template = Handlebars.compile(source);
			var html = template(history);
			$('#camera_history').append(html);
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

	renderCamera: function(camera_id) {
		App.API.Cameras.getCamera(camera_id).then(function(data) {
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
			App.UI.Cameras.getHistory({camera_id: camera_id});

		});
	},

	viewEvent: function(params) {
		console.log(params);
		App.API.Cameras.getEventData(params).then(function(data) {
			var source   = $("#view_event-template").html();
			var template = Handlebars.compile(source);
			var html    = template(data);
			$("#view_event").html(html);
			App.changePage('view_event');
			App.setActiveNav('cameras_link');

			$('#view_event .progress-slider').slider({
				max: $("#view_event .event-frame").length - 1
			}).on('slide', function(ev) {
				$('#event_video').data('next-frame-index', ev.value);
				$('#view_event').data('sliding', true);
				App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"), true, 1);
			}).on('slideStart', function(ev) {
				$('#view_event .pause-btn').hide();
				$('#view_event .play-btn').show();
			}).on('slideStop', function(ev) {
				$('#view_event').data('sliding', false);
			});

			$('#view_event .pause-btn').on('click', function(e){
				$('#view_event .pause-btn').hide();
				$('#view_event .play-btn').show();
			});

			$('#view_event .play-btn').on('click', function(e){
				$('#view_event .pause-btn').show();
				$('#view_event .play-btn').hide();
				App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"), false, 1);
			});

			$('#view_event .forward-btn').on('click', function(e){
				$('#view_event .pause-btn').hide();
				$('#view_event .play-btn').show();
				App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"), true, 1);
			});

			$('#view_event .backward-btn').on('click', function(e){
				$('#view_event .pause-btn').hide();
				$('#view_event .play-btn').show();
				App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"), true, -1);
			});

			$("#event_video").on('load',function(e) {
				if($('#view_event .pause-btn').is(':visible')) {
					setTimeout( function() {
						App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"), false, 1);
					}, 500);
				}
			});
			App.UI.Cameras.startVideo($("#event_video"), $("#view_event .event-frame"));
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

	startVideo: function(view_element, frames, ignore_pause, direction) {
		if(!frames.length) {
			return;
		}
		if($('#view_event .play-btn').is(':visible') && !ignore_pause) {
			return;
		}
		//console.log(frames);
		var index = view_element.data('next-frame-index');
		if(!index) {
			index = 0;
		}
		view_element.attr('src', $(frames[index]).attr('src'));
		var frame = frames[index];
		//console.log(frame);
		//console.log(index);

		var next_index = index + direction;
		if(next_index >= frames.length) {
			next_index = 0;
		}
		if(next_index < 0) {
			next_index = frames.length - 1;
		}
		if($('#event_video').data('sliding') !== true) {
			$('#view_event .progress-slider').slider('setValue', index);
		}
		//$('#view_event .video-progress').attr('aria-valuenow', (index / frames.length) * 100);
		//$('#view_event .video-progress').css('width', ((index / frames.length) * 100) + '%');


		view_element.data('next-frame-index', next_index);
		//setTimeout(1000, App.UI.Cameras.startVideo(view_element, frames));
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

	$(document).on('click', '.show-event-link', function( event ) {
		var target = $(this);
		console.log(target);
		var camera_id = target.data('camera-id');
		var event_id = target.data('event-id');
		var date = target.data('date');

		var params = {
			camera_id: camera_id,
			event_id: event_id,
			date: date
		};

		App.API.Cameras.getEventData(params).then(function(data) {
			console.log(data);
			var source = $('#event_info-template').html();
			var template = Handlebars.compile(source);
			var html = template(data);
			$('#event_info .modal-body').html(html);
		},
		function(error) {
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}

		});
	});

});