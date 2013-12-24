App.API.Motion = {
	testServer: function(url) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'motion.php', {action: 'test_config', test_url: url}, function(data) {
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

	saveConfigurationSettings: function(form_data) {
		var dfd = new jQuery.Deferred();
		$.post(App.API_URL + 'motion.php', form_data, function(data) {
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

	getMotionServers: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'motion.php',  {action: 'get_motion_servers'}, function(data) {
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

	getMotionServer: function(motion_id) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'motion.php',  {action: 'get_motion_server', motion_id: motion_id}, function(data) {
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

	getThreads: function(motion_id) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'motion.php',  {action: 'get_motion_threads', motion_id: motion_id}, function(data) {
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

	getThread: function(motion_id, thread_number) {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'motion.php',  {action: 'get_motion_thread', motion_id: motion_id, thread_number: thread_number}, function(data) {
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

	deleteMotionServer: function(motion_id) {
		var dfd = new jQuery.Deferred();
		$.ajax({
			url: App.API_URL + 'motion.php?motion_id=' + motion_id,
			data: {id: motion_id },
			type: 'DELETE',
			success: function(result) {
				dfd.resolve(result);
			}
		}).fail(function(error) {
			if(error.responseJSON) {
				dfd.reject(error.responseJSON);
			}
			else {
				dfd.reject(error.responseText);
				alert(error.responseText);
			}
		});
		return dfd;

	},
};