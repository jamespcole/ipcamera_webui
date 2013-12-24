<?

// $camurl="http://admin:w1llows1@127.0.0.1:8081";

// $boundary="\n--";

// $f = fopen($camurl,"r") ;

//    if(!$f)
//    {
//         //**** cannot open
//         echo "error";
//    }
//     else
//   {
//   		$r = '';
//         //**** URL OK
//          while (substr_count($r,"Content-Length") != 2) $r.=fread($f,512);

//          $start = strpos($r,"\xff");
//          $end   = strpos($r,$boundary,$start)-1;
//          $frame = substr("$r",$start,$end - $start);

//          header("Content-type: image/jpeg");
//          echo $frame;

//    }


// fclose($f);
// die();

	require_once('config.php');
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
				else {
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
			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);
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
						$thread['image'] = $url;
						$thread['test_image'] = BASE_URL.'api/image_proxy.php?configuration_test=true&is_stream=true&test_url='.urlencode($url);
						array_push($threads, $thread);
					}
				}

				$results['result'] = 'ok';
				$results['message'] = 'Motion server contacted successfully';
				$results['threads'] = $threads;
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
		$motion_data->id = $new_id;
		$motion_data->name = $_POST['name'];
		$motion_data->username = $_POST['username'];
		$motion_data->password = $_POST['password'];
		$motion_data->server = $_POST['server'];
		$motion_data->port = $_POST['port'];
		$motion_data->url = $_POST['url'];
		$motion_data->protocol = $_POST['protocol'];

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

		if(!$is_valid) {
			return $result;
		}
		return TRUE;
	}
?>