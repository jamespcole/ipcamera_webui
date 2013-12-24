App.API.Settings = {

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
	},

	getUserSettings: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'settings.php', {}, function(data) {
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