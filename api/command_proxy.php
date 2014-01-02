<?
	require_once('config.php');
	require_once('security.php');
	if(isset($_GET['action'])) {
		$results = array();
		$action = $_GET['action'];
		try {
			if($action == 'send_camera_command') {
				if(!isset($_GET['camera_id'])) {
					throw new Exception('No camera id was passed');
				}
				$camera_id = $_GET['camera_id'];

				if(!isset($_GET['command_index'])) {
					throw new Exception('No command index was passed.');
				}
				$command_index = $_GET['command_index'];

				$command_url = '';
				if(isset($_GET['command_url'])) {
					$command_url = $_GET['command_url'];
				}

				$json_file = CAMERA_DATA_PATH.'/'.$camera_id.'/camera.json';

				if(file_exists($json_file)) {
					$camera_data = json_decode(file_get_contents($json_file));
					if($command_index < count($camera_data->commands)) {
						$command = $camera_data->commands[$command_index];
						$url = '';
						if($command_url) {
							$url = $camera_data->base_url.$command_url;
							//echo file_get_contents($camera_data->base_url.$command_url);
						}
						else {
							$url = $command->full_command_url;
							//echo file_get_contents($command->full_command_url);
						}
						if(isset($_GET['command_params'])) {
							$command_params = $_GET['command_params'];
							if($command_params) {
								$params_obj = json_decode($command_params);
								$qstr = http_build_query($params_obj);
								if(strpos($url, '?') === FALSE) {
									$url .= '?';
								}
								else {
									$url .= '&';
								}
								$url .= $qstr;
							}

						}
						echo file_get_contents($url);
					}
					else {
						throw new Exception('The specified command index could not be found');
					}
					die();
				}
				else {
					throw new Exception('The specified camera could not be found');
				}
			}
		}
		catch(Exception $ex) {
			$results['result'] = 'error';
			$results['message'] = $ex->getMessage();
			header("HTTP/1.1 403 Unauthorized" );
			header('Content-Type: application/json');
			echo json_encode($results);
			die();
		}

	}
	else if(isset($_GET['command_url'])) {
		echo file_get_contents($_GET['command_url']);
	}
?>