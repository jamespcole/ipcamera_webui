<?
	require_once('config.php');
	require_once('security.php');
	require_once('motion_functions.php');
	header('Content-Type: application/json');

	$path = MOTION_DATA_PATH.'/';

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {

		if(!empty($_GET['action']) && $_GET['action'] == 'test_config') {
			$results = array();
			try {
				$test_url = (!empty($_GET['test_url'])) ? $_GET['test_url'] : '';
				if(!$test_url) {
					throw new Exception('No motion url was passed to test.');
				}

				$result = @file_get_contents($test_url);
				if($result === FALSE) {
					throw new Exception('Could not connect to motion server.');
				}

				if(strpos($result, '<html>') === 0) {
					throw new Exception('Motion is returning html, make sure you have the control_html_output setting set to off in motion.conf');
				}

				$results['result'] = 'ok';
				$results['message'] = 'Motion server contacted successfully';
				$results['motion_reponse'] = $result;


			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);
		}

		if(!empty($_GET['action']) && $_GET['action'] == 'get_motion_servers') {
			$result['motion_servers'] = getMotionServers();
			echo json_encode($result);
			die();
		}

		if(!empty($_GET['action']) && $_GET['action'] == 'get_motion_server') {
			$result = array();
			try {
				$motion_id = (!empty($_GET['motion_id'])) ? $_GET['motion_id'] : '';
				if(!$motion_id) {
					throw new Exception('No motion id was passed.');
				}
				$result = loadMotionData($motion_id);
			}
			catch(Exception $ex) {
				$result['result'] = 'error';
				$result['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($result);
			die();
		}

		if(!empty($_GET['action']) && $_GET['action'] == 'get_motion_threads') {
			$results = array();
			try {
				$motion_id = (!empty($_GET['motion_id'])) ? $_GET['motion_id'] : '';
				if(!$motion_id) {
					throw new Exception('No motion id was passed.');
				}

				$motion_data = loadMotionData($motion_id);

				$result = @file_get_contents($motion_data->url);
				if($result === FALSE) {
					throw new Exception('Could not connect to motion server.');
				}

				if(strpos($result, '<html>') === 0) {
					throw new Exception('Motion is returning html, make sure you have the control_html_output setting set to off in motion.conf');
				}
				$threads = array();
				$thread_bits = explode("\n", $result);
				if(count($thread_bits) > 1) {
					$start = 1;
					if(count($thread_bits) > 2) {
						$start = 2;
					}
					for($i = $start; $i < count($thread_bits); $i++) {
						$num = $thread_bits[$i];
						if($num == '') {
							break;
						}
						$thread = array();
						$thread_data = getThreadProperties($motion_data, $num);
						$thread['thread_data'] = $thread_data;
						$thread['name'] = "Thread ".$num;
						$url = $motion_data->protocol.'://';
						if($motion_data->username != '' && $motion_data->password != '') {
							$url .= $motion_data->username.':'.$motion_data->password.'@';
						}
						$url .=  $motion_data->server.':'.$thread_data['webcam_port'];
						$thread['number'] = $num;
						$thread['motion_id'] = $motion_data->id;
						//$thread['image'] = $url;
						//$thread['test_image'] = BASE_URL.'api/image_proxy.php?configuration_test=true&is_stream=true&test_url='.urlencode($url);
						$thread['image'] = BASE_URL.'api/image_proxy.php?configuration_test=true&is_stream=true&test_url='.urlencode($url);
						array_push($threads, $thread);
					}
				}

				$results['result'] = 'ok';
				$results['message'] = 'Motion server contacted successfully';
				$results['threads'] = $threads;

				$config_ok = checkMainConfigWriteable($motion_data);
				$config_check = array();
				$config_check['can_write'] = $config_ok;
				if($config_ok) {
					$config_check['message'] = 'Main config file is writable.';
				}
				else {
					$config_check['message'] = 'Main config file is not writable.';
				}
				$results['config_check'] = $config_check;

			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);
		}
		if(!empty($_GET['action']) && $_GET['action'] == 'get_motion_thread') {
			$results = array();
			try {
				$motion_id = (!empty($_GET['motion_id'])) ? $_GET['motion_id'] : '';
				if(!$motion_id) {
					throw new Exception('No motion id was passed.');
				}

				$thread_number = (isset($_GET['thread_number'])) ? $_GET['thread_number'] : '';
				if($thread_number === '') {
					throw new Exception('No thread_number was passed.');
				}

				$motion_data = loadMotionData($motion_id);

				$result = @file_get_contents($motion_data->url);
				if($result === FALSE) {
					throw new Exception('Could not connect to motion server.');
				}

				if(strpos($result, '<html>') === 0) {
					throw new Exception('Motion is returning html, make sure you have the control_html_output setting set to off in motion.conf');
				}
				$thread = array();
				$thread_bits = explode("\n", $result);
				if(count($thread_bits) > 1) {
					//$thread = array();
					$thread['name'] = "Thread ".$thread_number;
					$thread_data = getThreadProperties($motion_data, $thread_number);
					$thread['thread_data'] = $thread_data;
					$thread['thread_number'] = $thread_number;
					$thread['motion_id'] = $motion_data->id;

					if(empty($thread_data['netcam_url'])) {
						$url = $motion_data->protocol.'://';
						if($motion_data->username != '' && $motion_data->password != '') {
							$url .= $motion_data->username.':'.$motion_data->password.'@';
						}
						$port = ($motion_data->port + $thread_number + 1);
						$url .=  $motion_data->server.':'.$port;

						$thread['test_image'] = BASE_URL.'api/image_proxy.php?configuration_test=true&is_stream=true&test_url='.urlencode($url);
						$thread['server'] = $motion_data->server;
						$thread['port'] = $port;
						$thread['protocol'] = $motion_data->protocol;
						$thread['username'] = $motion_data->username;
						$thread['password'] = $motion_data->password;
						$thread['is_motion_stream'] = TRUE;
					}
					else {
						$url_components = parse_url($thread_data['netcam_url']);
						$thread['server'] = $url_components['host'];
						$thread['port'] = isset($url_components['port']) ? $url_components['port'] : 80;
						$thread['protocol'] = $url_components['scheme'];
						$thread['camera_image'] = ltrim($url_components['path'], '/');
						if($thread_data['netcam_userpass']) {
							$auth = explode(':', $thread_data['netcam_userpass']);
							if(count($auth) > 1) {
								$thread['username'] = $auth[0];
								$thread['password'] = $auth[1];
							}
						}
					}
				}

				$results['result'] = 'ok';
				$results['message'] = 'Motion server contacted successfully';
				$results['thread'] = $thread;
			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);
		}
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$val_result = validate();
		if($val_result !== TRUE) {
			header("HTTP/1.1 403 Unauthorized" );
			echo json_encode($val_result);
			die();
		}

		$new_id = time();
		$is_new = TRUE;
		if(isset($_POST['id']) && $_POST['id'] != '') {
			$new_id = $_POST['id'];
			$is_new = FALSE;
		}
		$motion_data = json_decode('{}');
		if(!$is_new) {
			$motion_data = loadMotionData($new_id);
		}
		$motion_data->id = $new_id;
		$motion_data->name = $_POST['name'];
		$motion_data->username = $_POST['username'];
		$motion_data->password = $_POST['password'];
		$motion_data->server = $_POST['server'];
		$motion_data->port = $_POST['port'];
		$motion_data->url = $_POST['url'];
		$motion_data->protocol = $_POST['protocol'];
		$motion_data->config_file = $_POST['config_file'];

		header('Content-Type: application/json');
		$json_data = json_encode($motion_data, JSON_PRETTY_PRINT);
		if(is_writable($path)) {
			$new_path = $path.$new_id.'/';
			if($is_new) {
				mkdir($new_path);
				chmod($new_path, 0775);
			}

			file_put_contents($new_path.'server.json', $json_data);
			chmod($new_path.'server.json', 0775);
			echo $json_data;
			die();
		}
		else {
			header("HTTP/1.1 403 Unauthorized" );
			$error_data = json_decode('{}');
			$error_data->errors = array();
			$message = 'Could not save motion server data';
			$reason = 'The motion server data folder does not have the correct permissions set.  PHP needs write permissions to save camera settings.  ';
			$reason .=  'To fix this error you need to run the command "chmod -R a+w '.realpath(getcwd().'/'.$path).'"';
			array_push($error_data->errors, array('type' => 'global', 'message' => $message, 'reason' => $reason));
			$json_data = json_encode($error_data);
			echo $json_data;
			die();
		}
	}

	if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
		$result = array();
		if(isset($_GET['motion_id'])) {
			$id = $_GET['motion_id'];
			if(!rrmdir($path.$id)) {
				header("HTTP/1.1 403 Unauthorized" );
				$result['errors'] = array('type' => 'global', 'message' => 'Could not delete motion server', 'reason' => 'The motion server configuration could not be deleted.');
			}
			else {
				$result['message'] = 'Camera deleted successfully';
			}
		}
		else {
			header("HTTP/1.1 403 Unauthorized" );
			$result['errors'] = array('type' => 'global', 'message' => 'Could not delete motion server', 'reason' => 'No motion server id was passed in to the API.');
		}
		header('Content-Type: application/json');
		$json_data = json_encode($result);
		echo $json_data;
		die();
	}
	//echo file_get_contents('http://192.168.1.250:8080/0/config/list');

	function validate() {
		$result = json_decode('{}');
		$result->errors = array();
		$is_valid = TRUE;
		if(!isset($_POST['name']) || $_POST['name'] == '') {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'name',
				'message' => 'You must select a name',
				'reason' => 'You must set a name to log in with'
			));
		}

		if(!isset($_POST['server']) || $_POST['server'] == '') {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'server',
				'message' => 'You must select a server',
				'reason' => 'You must set a address for the motion server'
			));
		}

		if(!isset($_POST['port']) || $_POST['port'] == '') {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'port',
				'message' => 'You must select a port',
				'reason' => 'You must set a port for the motion server'
			));
		}

		if(!isset($_POST['config_file']) || $_POST['config_file'] == '') {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'config_file',
				'message' => 'You must select a config file location',
				'reason' => 'You must set a config file location for the motion server'
			));
		}

		if(isset($_POST['config_file']) && !file_exists($_POST['config_file'])) {
			$is_valid = FALSE;
			array_push($result->errors, array(
				'type' => 'field',
				'name' => 'config_file',
				'message' => 'The motion config file could not be found.',
				'reason' => 'The motion config file could not be found at this location'
			));
		}

		if(!$is_valid) {
			return $result;
		}
		return TRUE;
	}

	function rrmdir($dir) {
		foreach(glob($dir . '/*') as $file) {
			if(is_dir($file)) {
				rrmdir($file);
			}
			else {
				unlink($file);
			}
		}
		return rmdir($dir);
	}
?>