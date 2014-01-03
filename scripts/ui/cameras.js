App.UI.Cameras = {
	current_camera:null,

	updateStatus: function() {
		if(App.UI.Cameras.current_camera.status_handlers) {
			for(var i = 0; i < App.UI.Cameras.current_camera.status_handlers.length; i++) {
				var status_handler = App.UI.Cameras.current_camera.status_handlers[i];
				App.API.Cameras.getStatusData({
						camera_id: App.UI.Cameras.current_camera.id,
						status_parser_index: i
					}).then(function(data) {
					var parser = eval('var fn = ' + status_handler.status_parser);
					var status_data = fn(data.result, App.UI.Cameras.current_camera);
					$('.camera-command').each(function(key, val) {
						//console.log(val);
						var command = App.UI.Cameras.current_camera.commands[$(val).data('command-index')];
						//var func_str = $(val).data('status-handler');
						var func_str = command.status_handler;
						if(func_str) {
							var status_handler = eval('var fnStatusHandler = ' + func_str);
							var result = fnStatusHandler($(val), status_data, command, App.UI.Cameras.current_camera);
						}

						/*var func_str = $(val).data('status-handler');
						if(!func_str) {
							continue++;
						}
						var parser = eval('var fn = ' + func_str);
						var result = fn($(val), );*/
					});
				});
			}
		}
	},

	getHistory: function(params) {
		params['page'] = 1;
		params['rpp'] = $('#view_camera .more-events-btn').data('rpp');
		App.API.Cameras.getHistory(params).then(function(history) {
			var source = $('#history-template').html();
			var template = Handlebars.compile(source);
			var html = template(history);
			$('#camera_history').append(html);
			if(history.events.length == params['rpp']) {
				$('#view_camera .more-events-btn').show();
			}
		},
		function(error) {
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}
			$('#view_camera .more-events-btn').show();
		});
	},

	renderCamera: function(camera_id) {
		App.API.Cameras.getCamera(camera_id).then(function(data) {
			App.UI.Cameras.current_camera = data.camera;
			var source   = $("#view_camera-template").html();
			var template = Handlebars.compile(source);
			var html    = template(data.camera);
			$("#view_camera").html(html);
			App.changePage('view_camera');
			App.setActiveNav('cameras_link');
			App.camera_updaters = [];

			var camera = data.camera;

			var element = $('#active_camera_image');
			element.on('load', function(event) {
				App.UI.Cameras.imageLoaded(this);
			});
			if(App.use_streams) {
				element.on('error', function(event) {
					var target = $(this);
					if(target.attr('src') == target.data('stream-url')) {
						console.log('loading stream failed, falling back to polling mode');

						var updater = new CameraUpdater({element: target, url: target.data('snapshot-url')});
						updater.init();
						updater.start();
						App.camera_updaters['updater_active_camera_image'] = updater;
					}
				});
				element.attr('src', element.data('stream-url'));
			}
			else {
				if(!App.camera_updaters['updater_active_camera_image']) {
					var updater = new CameraUpdater({element: element, url: element.data('snapshot-url')});
					updater.init();
					updater.start();
					App.camera_updaters['updater_active_camera_image'] = updater;
				}
			}
				/*var updater = new CameraUpdater({element: element, url: camera.image_url});
				updater.init();
				updater.start();
				App.camera_updaters['active_camera_image'] = updater;*/
			App.UI.Cameras.updateStatus();
			App.UI.Cameras.getHistory({camera_id: camera_id});
			if(camera.motion_id) {
				App.API.Cameras.getDetectionStatus({camera_id: camera_id}).then(function(data) {
					if(data.detection_status == 'ACTIVE') {
						$("#view_camera .pause-detection-btn").show();
						$("#view_camera .resume-detection-btn").hide();
					}
					else if(data.detection_status == 'PAUSE') {
						$("#view_camera .pause-detection-btn").hide();
						$("#view_camera .resume-detection-btn").show();
					}
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
		});
	},

	viewEvent: function(params) {
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
	},

	//this is a hack to get around the load event on images failing
	//when the image is loaded into a handlebars template
	imageLoaded: function(target) {
		$(target).parent('.image-loading').removeClass('image-loading').addClass('image-loaded');
	}


};

$( document ).ready(function() {

	$(document).on('click', '.camera-command', function(e) {

		var command_index = $(this).data('command-index');
		var command = App.UI.Cameras.current_camera.commands[command_index];

		var func_str = command.before_command_handler;
		var command_params = '';
		if(func_str) {
			var before_command_handler = eval('var fnBeforeCommandHandler = ' + func_str);
			var result = fnBeforeCommandHandler($(this), command, App.UI.Cameras.current_camera);
			if(result && result.command_params) {
				command_params = result.command_params;
			}
		}

		var c_url = $(this).data('command-url');
		var camera_id = $(this).data('camera-id');

		/*$.get(App.API_URL + 'command_proxy.php', {
			command_url: c_url,
			command_index: command_index,
			camera_id: camera_id
		});*/
		var params = {
			//command_url: c_url,
			command_index: command_index,
			camera_id: camera_id,
			action: 'send_camera_command'
		};
		if(command_params) {
			params.command_params = JSON.stringify(command_params);
		}
		$(this).attr('disabled', 'disabled');
		var element = this;
		App.API.Cameras.sendCameraCommand(params).then(function(command_result) {
			$(element).removeAttr('disabled');
			App.UI.Cameras.updateStatus();
			var func_str = command.after_command_handler;
			if(func_str) {
				var after_command_handler = eval('var fnAfterCommandHandler = ' + func_str);
				var result = fnAfterCommandHandler($(element), command, App.UI.Cameras.current_camera, command_result);
			}
		},
		function(error) {
			$(element).removeAttr('disabled');
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}
		});


	});

	$(document).on('click', '.more-events-btn', function( event ) {
		var target = $(this);
		var camera_id = target.data('camera-id');
		var page = target.data('page');
		var rpp = target.data('rpp');

		var params = {
			camera_id: camera_id,
			page: page + 1,
			rpp: rpp
		};
		target.hide();
		App.API.Cameras.getHistory(params).then(function(history) {
			var source = $('#history-template').html();
			var template = Handlebars.compile(source);
			var html = template(history);
			$('#camera_history').append(html);
			if(history.events.length == rpp) {
				target.show();
			}
			target.data('page', page + 1);
			//console.log(history);
		},
		function(error) {
			target.show();
			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}

		});
	});

	$(document).on('click', '.motion-detection-btn', function( event ) {
		var target = $(this);
		var camera_id = target.data('camera-id');
		var status = target.data('status');

		var params = {
			camera_id: camera_id,
			new_status: status
		};
		$("#view_camera .pause-detection-btn").hide();
		$("#view_camera .resume-detection-btn").hide();
		$("#view_camera .status-updating-loader").show();
		App.API.Cameras.setDetectionStatus(params).then(function(data) {
			if(data.result) {
				if(status == 'pause') {
					$("#view_camera .pause-detection-btn").hide();
					$("#view_camera .resume-detection-btn").show();
				}
				else if(status == 'start') {
					$("#view_camera .pause-detection-btn").show();
					$("#view_camera .resume-detection-btn").hide();
				}
			}
			$("#view_camera .status-updating-loader").hide();
		},
		function(error) {
			if(status == 'pause') {
				$("#view_camera .pause-detection-btn").show();
				$("#view_camera .resume-detection-btn").hide();
			}
			else if(status == 'start') {
				$("#view_camera .pause-detection-btn").hide();
				$("#view_camera .resume-detection-btn").show();
			}
			$("#view_camera .status-updating-loader").hide();

			if(error.message) {
				App.showGlobalError("Could not connect to API", error.message, true);
			}
			else {
				App.showGlobalError("Could not connect to API", "An error occurred while trying to communicate with the API.", true);
			}

		});
	});

});