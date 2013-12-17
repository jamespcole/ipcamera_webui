<?
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$base_url = $protocol.$_SERVER['HTTP_HOST'].str_replace('api/'.basename(__FILE__), '', $_SERVER['PHP_SELF']);
//echo $base_url;
define('BASE_URL', $base_url);

$path = '../cameras/';
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
	$camera_data = json_decode('{}');
	$camera_data->id = $new_id;
	$camera_data->name = $_POST['name'];
	$camera_data->username = $_POST['username'];
	$camera_data->password = $_POST['password'];
	$camera_data->server = $_POST['server'];
	$camera_data->port = $_POST['port'];
	$camera_data->camera_image = $_POST['camera_image'];
	$camera_data->base_url = $_POST['base_url'];

	if(isset($_POST['proxy_data']) && $_POST['proxy_data'] == 'on') {
		$camera_data->image_url = 'api/image_proxy.php?camera_id='.$new_id;
		$camera_data->real_image_url = $_POST['image_url'];
		$camera_data->proxy_data = TRUE;
	}
	else {
		$camera_data->image_url = $_POST['image_url'];
		$camera_data->real_image_url = $_POST['image_url'];
		$camera_data->proxy_data = FALSE;
	}

	if(isset($_POST['command_url'])) {
		$commands = array();
		$count = 0;
		foreach($_POST['command_url'] as $command_url) {
			array_push($commands, array(
				'command_url' => $_POST['command_url'][$count],
				'button_text' => $_POST['button_text'][$count],
				'command_icon' => $_POST['command_icon'][$count],
				'full_command_url' => $camera_data->base_url.$_POST['command_url'][$count],
			));
			$count++;
		}
		$camera_data->commands = $commands;
	}

	header('Content-Type: application/json');
	$json_data = json_encode($camera_data, JSON_PRETTY_PRINT);
	if(is_writable($path)) {
		$new_path = $path.$new_id.'/';
		if($is_new) {
			mkdir($new_path);
			chmod($new_path, 0775);
		}

		file_put_contents($new_path.'camera.json', $json_data);
		chmod($new_path.'camera.json', 0775);
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

header('Content-Type: application/json');
echo json_encode($result);
?>