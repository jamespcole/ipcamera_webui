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
		var html = template({port: '8080', server: '127.0.0.1'});
		$('#edit_motion').html(html);
		App.changePage('edit_motion');
		App.setActiveNav('settings_link');
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
		console.log(url);
		return url;
	},

	importMotionCameras: function(motion_id) {
		console.log(motion_id);
		App.API.Motion.getThreads(motion_id).then(function(data) {
			var source = $('#import_motion_cameras-template').html();
			var template = Handlebars.compile(source);
			var html = template(data);
			$('#import_motion_cameras').html(html);
			App.changePage('import_motion_cameras');
			App.setActiveNav('settings_link');
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