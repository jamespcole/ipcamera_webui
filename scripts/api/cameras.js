App.API.Cameras = {

	getCameras: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', function(data) {
			dfd.resolve(data);
		});
		return dfd;
	},

	getCamera: function(camera_id, include_motion_servers) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', {id: camera_id, include_motion_servers: include_motion_servers }, function(data) {
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
	},

	sendCameraCommand: function(params) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'command_proxy.php', params, function(data) {
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
	},

	saveConfiguration: function(form_data) {
		var dfd = new jQuery.Deferred();
		$.post(App.API_URL + 'cameras.php', form_data, function(data) {
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
	},

	getHistory: function(params) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'history.php', params, function(data) {
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
	},

	getEventData: function(params) {
		var dfd = new jQuery.Deferred();
		params['action'] = 'event_data';
		$.get(App.API_URL + 'history.php', params, function(data) {
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
	},

	getDetectionStatus: function(params) {
		var dfd = new jQuery.Deferred();
		params['action'] = 'detection_status';
		$.get(App.API_URL + 'cameras.php', params, function(data) {
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
	},

	setDetectionStatus: function(params) {
		var dfd = new jQuery.Deferred();
		params['action'] = 'change_detection_status';
		$.get(App.API_URL + 'cameras.php', params, function(data) {
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
	},

	getCameraModels: function(params) {
		var dfd = new jQuery.Deferred();
		if(App.camera_models !== null) {
			dfd.resolve(App.camera_models);
			return dfd;
		}
		params['action'] = 'get_camera_models';
		$.get(App.API_URL + 'cameras.php', params, function(data) {
			App.camera_models = data;
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
	},

	getTestStatusData: function(params) {
		var dfd = new jQuery.Deferred();
		params['action'] = 'test_status_data';
		$.get(App.API_URL + 'cameras.php', params, function(data) {
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
	},

	getStatusData: function(params) {
		var dfd = new jQuery.Deferred();
		params['action'] = 'get_status_data';
		$.get(App.API_URL + 'cameras.php', params, function(data) {
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