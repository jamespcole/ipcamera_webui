<?
require_once('config.php');
require_once('security.php');
require_once('motion_functions.php');

$path = CAMERA_DATA_PATH.'/';

$result = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$val_result = validate();
	if($val_result !== TRUE) {
		header('Content-Type: application/json');
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
	$existing_motion_id = null;
	$camera_data = json_decode('{}');
	if(!$is_new) {
		$json_file = $path.$new_id.'/camera.json';
		if(file_exists($json_file)) {
			$camera_data = json_decode(file_get_contents($json_file));
			$existing_motion_id = $camera_data->motion_id;
		}
	}
	$camera_data->id = $new_id;
	$camera_data->protocol = $_POST['protocol'];
	$camera_data->name = $_POST['name'];
	$camera_data->username = $_POST['username'];
	$camera_data->password = $_POST['password'];
	$camera_data->server = $_POST['server'];
	$camera_data->port = $_POST['port'];
	$camera_data->camera_image = $_POST['camera_image'];
	$camera_data->base_url = $_POST['base_url'];
	$camera_data->motion_id = ($_POST['motion_id'] == 'none') ? '' : $_POST['motion_id'];
	$camera_data->thread_number = $_POST['thread_number'];
	$camera_data->model_id = $_POST['model_id'];

	if(isset($_POST['proxy_data']) && $_POST['proxy_data'] == 'on') {
		$camera_data->image_url = 'api/image_proxy.php?camera_id='.$new_id;
		$camera_data->snapshot_url = 'api/image_proxy.php?camera_id='.$new_id.'&snapshot=true';
		$camera_data->real_image_url = $_POST['image_url'];
		$camera_data->proxy_data = TRUE;
	}
	else {
		$camera_data->image_url = $_POST['image_url'];
		$camera_data->real_image_url = $_POST['image_url'];
		$camera_data->proxy_data = FALSE;
	}

	if(isset($_POST['update_commands']) && $_POST['update_commands'] == 'true') {
		$camera_data->commands = array();
		if(isset($_POST['command_url'])) {
			$commands = array();
			$count = 0;
			foreach($_POST['command_url'] as $command_url) {
				array_push($commands, array(
					'command_url' => $_POST['command_url'][$count],
					'button_text' => $_POST['button_text'][$count],
					'command_icon' => $_POST['command_icon'][$count],
					'full_command_url' => $camera_data->base_url.$_POST['command_url'][$count],
					'status_handler' => $_POST['status_handler'][$count],
					'before_command_handler' => $_POST['before_command_handler'][$count],
					'after_command_handler' => $_POST['after_command_handler'][$count],
					'command_type' => $_POST['command_type'][$count],
					'group_type' => $_POST['group_type'][$count],
					'control_size' => $_POST['control_size'][$count],
					'command_name' => $_POST['command_name'][$count],
				));
				$count++;
			}
			$camera_data->commands = $commands;
		}
	}

	if(isset($_POST['status_url'])) {
		$status_handlers = array();
		$count = 0;
		foreach($_POST['status_url'] as $status_url) {
			array_push($status_handlers, array(
				'status_url' => $_POST['status_url'][$count],
				'status_parser' => $_POST['status_parser'][$count],
				'full_status_url' => $camera_data->base_url.$_POST['status_url'][$count],
			));
			$count++;
		}
		$camera_data->status_handlers = $status_handlers;
	}

	$motion_data = null;
	if($camera_data->motion_id) {
		$motion_data = loadMotionData($camera_data->motion_id);
	}
	//update motion
	/*if($camera_data->motion_id) {
		$motion_data = loadMotionData($camera_data->motion_id);
		setConfigValue($motion_data, $camera_data->thread_number, 'target_dir', CAMERA_DATA_PATH.'/'.$camera_data->id.'/history');
		setConfigValue($motion_data, $camera_data->thread_number, 'snapshot_filename', urlencode('%Y-%m-%d/%v/%v_%Y-%m-%d_%H-%M-%S-snapshot'));
		setConfigValue($motion_data, $camera_data->thread_number, 'jpeg_filename',  urlencode('%Y-%m-%d/%v/%v_%Y-%m-%d_%H-%M-%S_%q'));
		setConfigValue($motion_data, $camera_data->thread_number, 'movie_filename',  urlencode('%Y-%m-%d/%v/%v_%Y-%m-%d_%H-%M-%S'));

		writeConfigValue($motion_data, $camera_data->thread_number);
	}*/

	header('Content-Type: application/json');
	$json_data = json_encode($camera_data, JSON_PRETTY_PRINT);
	if(is_writable($path)) {
		$new_path = $path.$new_id.'/';
		if($is_new) {
			mkdir($new_path);
			chmod($new_path, 0775);
			mkdir($new_path.'history');
			chmod($new_path.'history', 0775);

		}
		if($is_new && $camera_data->motion_id) { //if it is new and has a motion server selected
			$camera_data->motion_config = createMotionFile($camera_data);
			$json_data = json_encode($camera_data, JSON_PRETTY_PRINT);
			addThread($camera_data->motion_id, $camera_data->motion_config);
		}
		else if($existing_motion_id && !$camera_data->motion_id) { //if it used to have a motion server and it no longer does
			removeThread($existing_motion_id, $camera_data->motion_config);
		}
		else if($existing_motion_id && ($existing_motion_id != $camera_data->motion_id)) { //if we are changing the motion server
			$camera_data->motion_config = createMotionFile($camera_data);
			$json_data = json_encode($camera_data, JSON_PRETTY_PRINT);
			addThread($camera_data->motion_id, $camera_data->motion_config);
		}
		else if(!$existing_motion_id && $camera_data->motion_id) { //if we are adding an exisitng camera to the motion server
			$camera_data->motion_config = createMotionFile($camera_data);
			$json_data = json_encode($camera_data, JSON_PRETTY_PRINT);
			addThread($camera_data->motion_id, $camera_data->motion_config);
		}
		else if($existing_motion_id && ($existing_motion_id == $camera_data->motion_id)) { //if we are updating a camera and the motion server has not changed
			$thread_number = getThreadNumber($camera_data, $motion_data);

			setConfigValue($motion_data, $thread_number, 'netcam_url', $camera_data->protocol.'://'.$camera_data->server.':'.$camera_data->port.'/'.$camera_data->camera_image);
			$auth_info = '';
			if($camera_data->username) {
				$auth_info = $camera_data->username.':';
			}
			if($camera_data->password) {
				$auth_info .= $camera_data->password;
			}
			setConfigValue($motion_data, $thread_number, 'netcam_userpass', $auth_info);
			writeConfigValue($motion_data, $thread_number);

		}
		file_put_contents($new_path.'camera.json', $json_data);
		chmod($new_path.'camera.json', 0775);

		//clear out the cached session vars

		unset($_SESSION['image_url_'.$new_id]);
		unset($_SESSION['image_info_'.$new_id]);

		echo $json_data;
		die();
	}
	else {
		header("HTTP/1.1 403 Unauthorized" );
		$error_data = json_decode('{}');
		$error_data->errors = array();
		$message = 'Could not save camera data';
		$reason = 'The camera data folder does not have the correct permissions set.  PHP needs write permissions to save camera settings.  ';
		$reason .=  'To fix this error you need to run the command "chmod -R a+w '.realpath(getcwd().'/'.$path).'"';
		array_push($error_data->errors, array('type' => 'global', 'message' => $message, 'reason' => $reason));
		$json_data = json_encode($error_data);
		echo $json_data;
		die();
	}

}
else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
	$result = array();
	if(isset($_GET['camera_id'])) {
		$id = $_GET['camera_id'];
		$json_file = $path.$id.'/camera.json';
		if(file_exists($json_file)) {
			$camera_data = json_decode(file_get_contents($json_file));
			if($camera_data->motion_id && $camera_data->motion_config) {
				removeThread($camera_data->motion_id, $camera_data->motion_config);
			}
		}
		if(!rrmdir($path.$id)) {
			header("HTTP/1.1 403 Unauthorized" );
			$result['errors'] = array('type' => 'global', 'message' => 'Could not delete camera', 'reason' => 'The camera configuration could not be deleted.');
		}
		else {
			$result['message'] = 'Camera deleted successfully';
		}
	}
	else {
		header("HTTP/1.1 403 Unauthorized" );
		$result['errors'] = array('type' => 'global', 'message' => 'Could not delete camera', 'reason' => 'No camera id was passed in to the API.');
	}
	header('Content-Type: application/json');
	$json_data = json_encode($result);
	echo $json_data;
	die();
}
else if(isset($_GET['id'])) {
	$json_file = $path.$_GET['id'].'/camera.json';
	$result['camera'] = array();
	if(file_exists($json_file)) {
		$camera_data = json_decode(file_get_contents($json_file));
		$camera_data->id = $_GET['id'];
		$result['camera'] = $camera_data;
		if(isset($_GET['include_motion_servers']) && $_GET['include_motion_servers'] == 'true') {
			$result['motion_servers'] = getMotionServers();
		}
	}
}
else if(isset($_GET['action'])) {
	$results = array();
	$action = $_GET['action'];
	try {
		header('Content-Type: application/json');
		if($action == 'detection_status') {
			if(!isset($_GET['camera_id'])) {
				throw new Exception('No camera id was passed');
			}
			$camera_id = $_GET['camera_id'];
			$json_file = $path.$camera_id.'/camera.json';

			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$results['detection_status'] = getDetectionStatus($camera_data);
				echo json_encode($results);
				die();
			}
			else {
				throw new Exception('The specified camera could not be found');
			}
		}
		else if($action == "change_detection_status") {
			if(!isset($_GET['camera_id'])) {
				throw new Exception('No camera id was passed');
			}
			$camera_id = $_GET['camera_id'];
			if(!isset($_GET['new_status'])) {
				throw new Exception('No status was passed');
			}
			$new_status = $_GET['new_status'];
			if($new_status != 'start' &&  $new_status != 'pause') {
				throw new Exception('The new_status parameter must be either start or pause');
			}

			$json_file = $path.$camera_id.'/camera.json';
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$results['result'] = setDetectionStatus($camera_data, $new_status);
				echo json_encode($results);
				die();
			}
			else {
				throw new Exception('The specified camera could not be found');
			}
		}
		else if($action == "get_camera_models") {
			$json_file = DATA_PATH.'/camera_models.json';
			if(file_exists($json_file)) {
				/*echo file_get_contents($json_file);
				die();*/
				$camera_models_data = json_decode(file_get_contents($json_file));
				//print_r($camera_models_data);
				$results = $camera_models_data;
				usort($camera_models_data->cameras, "custom_sort");
				// Define the custom sort function

				echo json_encode($results);
				die();
			}
			else {
				throw new Exception('The camera models file could not be found');
			}
		}
		else if($action == "test_status_data") {
			if(!isset($_GET['test_status_url'])) {
				throw new Exception('No status url was passed');
			}
			$url = $_GET['test_status_url'];
			//echo file_get_contents($url);
			$results['result'] = file_get_contents($url);
			echo json_encode($results);
			die();
		}
		else if($action == "get_status_data") {
			if(!isset($_GET['camera_id'])) {
				throw new Exception('No camera id was passed');
			}
			$camera_id = $_GET['camera_id'];

			if(!isset($_GET['status_parser_index'])) {
				throw new Exception('No status_parser_index was passed');
			}
			$status_parser_index = $_GET['status_parser_index'];

			$json_file = $path.$camera_id.'/camera.json';
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				if(isset($camera_data->status_handlers)) {

					if(isset($camera_data->status_handlers[$status_parser_index]) && isset($camera_data->status_handlers[$status_parser_index]->full_status_url)) {
						$results['result'] = file_get_contents($camera_data->status_handlers[$status_parser_index]->full_status_url);
					}
				}

				echo json_encode($results);
				die();
			}
			else {
				throw new Exception('The specified camera could not be found');
			}

			//echo file_get_contents($url);
			$results['result'] = file_get_contents($url);
			echo json_encode($results);
			die();
		}
	}
	catch(Exception $ex) {
		$results['result'] = 'error';
		$results['message'] = $ex->getMessage();
		header("HTTP/1.1 403 Unauthorized" );
		echo json_encode($results);
		die();
	}

}
else {
	$contents = scandir($path);
	$result['cameras'] = array();
	foreach($contents as $item) {

		if($item == '.' || $item == '..') {
			continue;
		}
		if(is_dir($path.$item)) {
			$json_file = $path.$item.'/camera.json';
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$camera_data->id = $item;
				array_push($result['cameras'], $camera_data);
				//echo $camera_data;
			}
		}
	}

}

function validate() {
	$result = json_decode('{}');
	$result->errors = array();
	$is_valid = TRUE;
	if(!isset($_POST['name']) || $_POST['name'] == '') {
		$is_valid = FALSE;
		array_push($result->errors, array(
			'type' => 'field',
			'name' => 'name',
			'message' => 'A camera name is required',
			'reason' => 'You must choose a name to identify this camera'
		));
	}

	if(!isset($_POST['server']) || $_POST['server'] == '') {
		$is_valid = FALSE;
		array_push($result->errors, array(
			'type' => 'field',
			'name' => 'server',
			'message' => 'A server address is required',
			'reason' => 'You must specifiy the address of the camera'
		));
	}

	if(!isset($_POST['port']) || $_POST['port'] == '') {
		$is_valid = FALSE;
		array_push($result->errors, array(
			'type' => 'field',
			'name' => 'port',
			'message' => 'A server port is required',
			'reason' => 'You must specifiy the port used by the camera'
		));
	}

	if(!isset($_POST['camera_image']) || $_POST['camera_image'] == '') {
		$is_valid = FALSE;
		array_push($result->errors, array(
			'type' => 'field',
			'name' => 'camera_image',
			'message' => 'You must enter the url used to access the camera image',
			'reason' => 'You must enter the url used to access the camera image'
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

function custom_sort($a,$b) {
	return $a->brand.$a->name > $b->brand.$b->name;
}

header('Content-Type: application/json');
echo json_encode($result);
?>