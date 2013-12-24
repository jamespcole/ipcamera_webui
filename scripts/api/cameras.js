App.API.Cameras = {

	getCameras: function() {
		var dfd = new jQuery.Deferred();
		$.get(App.API_URL + 'cameras.php', function(data) {
			dfd.resolve(data);
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
		/*App.showGlobalError(null, null, false);
		var camera_id = $('#edit_camera_id').val();
		var is_new = (camera_id) ? false : true;
		//hide the validation errors before trying to save
		$('#configuration_form .form-group').removeClass('has-error');
		$('#configuration_form .validation-message').html('').hide();

		$.post(App.API_URL + 'cameras.php', form_data, function(data) {
			//console.log('success', data);
			App.showConfigurationSaveSuccess(data, is_new);

		}).fail(function(data) {
			if(data.responseJSON) {
				var error_data = data.responseJSON;
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
			}
			else {
				alert(data.responseText);
			}
		});*/
	},

};