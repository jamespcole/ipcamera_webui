<?
	require_once('config.php');
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$result = array();
		try {
			session_start();
			header('Content-Type: application/json');

			if(!isset($_POST['username']) || !isset($_POST['username'])) {
				throw new Exception('Incorrect username or password');
			}

			$user_data = json_decode('{}');
			if(!file_exists(USER_DATA_PATH)) {
				throw new Exception('User data not found');
			}
			$user_data = json_decode(file_get_contents(USER_DATA_PATH));
			if($user_data->username != $_POST['username']) {
				throw new Exception('Incorrect username or password');
			}

			if($user_data->password_hash != crypt($_POST['password'], $user_data->salt)) {
				throw new Exception('Incorrect username or password');
			}

			$_SESSION['logged_in'] = TRUE;
			$result['result'] = 'ok';
			$result['message'] = 'Log in successfull';
			echo json_encode($result);
			die();
		}
		catch(Exception $ex) {
			$result['result'] = 'error';
			$result['message'] = $ex->getMessage();
			header("HTTP/1.1 403 Unauthorized" );
			echo json_encode($result);
			die();
		}
	}
	else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if(isset($_GET['action'])) {
			if($_GET['action'] == 'logout') {
				session_start();
				session_destroy();
				header('Content-Type: application/json');
				$result['result'] = 'ok';
				$result['message'] = 'Logged out';
				echo json_encode($result);
				die();
			}
		}
	}
?>