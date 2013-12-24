<?
	require_once('config.php');
	if(!FIRST_RUN) {
		require_once('security.php');
	}
	header('Content-Type: application/json');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$user_data = json_decode('{}');
		if(file_exists(USER_DATA_PATH)) {
			$user_data = json_decode(file_get_contents(USER_DATA_PATH));
		}
		echo json_encode($user_data);
	}
	else if($_SERVER['REQUEST_METHOD'] === 'POST') {

		$val_result = validate();
		if($val_result !== TRUE) {
			header("HTTP/1.1 403 Unauthorized" );
			echo json_encode($val_result);
			die();
		}

		$user_data = json_decode('{}');
		if(file_exists(USER_DATA_PATH)) {
			$user_data = json_decode(file_get_contents(USER_DATA_PATH));
		}
		$user_data->username = (!empty($_POST['username'])) ? $_POST['username'] : '';
		//$user_data->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		if(!empty($_POST['password'])) {
			$user_data->salt = time();
			$user_data->password_hash = crypt($_POST['password'], $user_data->salt);
		}

		$user_data->date_format = (!empty($_POST['date_format'])) ? $_POST['date_format'] : '';
		$user_data->time_format = (!empty($_POST['time_format'])) ? $_POST['time_format'] : '';


		$json_data = json_encode($user_data);
		file_put_contents(USER_DATA_PATH, $json_data);
		chmod(USER_DATA_PATH, 0775);
		if(FIRST_RUN) {
			session_start();
			$_SESSION['logged_in'] = TRUE;
		}
		echo $json_data;
		/*$camera_data->username = $_POST['username'];
		$camera_data->password = $_POST['password'];
		$camera_data->server = $_POST['server'];
		$camera_data->port = $_POST['port'];
		$camera_data->camera_image = $_POST['camera_image'];
		$camera_data->base_url = $_POST['base_url'];*/
	}

	function validate() {
		$result = json_decode('{}');
		$result->errors = array();
		$is_valid = TRUE;
		if(!isset($_POST['username']) || $_POST['username'] == '') {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'username',
				'message' => 'You must select a username',
				'reason' => 'You must set a username to log in with'
			));
		}

		$password = '';
		if(FIRST_RUN) {
			if(!isset($_POST['password']) || $_POST['password'] == '') {
				$is_valid = FALSE;
				array_push($result->errors, array(
					'type' => 'field',
					'name' => 'password',
					'message' => 'You must choose a password',
					'reason' => 'You did not enter a password'
				));
			}
			else {
				$password = $_POST['password'];
			}
		}

		if($password !== '' && strlen($password) < 8) {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'confirm_password',
				'message' => 'Your password must be at least 8 characters',
				'reason' => 'Your password is too short'
			));
		}

		if($password !== '') {
			if(!isset($_POST['confirm_password']) || $_POST['confirm_password'] != $password) {
				$is_valid = FALSE;
				array_push($result->errors, array(
					'type' => 'field',
					'name' => 'confirm_password',
					'message' => 'Your passwords did not match',
					'reason' => 'You must confirm your password'
				));
			}
		}

		if(!$is_valid) {
			return $result;
		}
		return TRUE;
	}
?>