<?
	require_once('config.php');
	session_start();
	$path = CAMERA_DATA_PATH.'/';
	//for testing configuration before saving
	if(isset($_GET['configuration_test'])) {
		if(isset($_GET['test_url'])) {
			$realpath = $_GET['test_url'];
			header("Content-Type: image/jpeg");
			echo file_get_contents($realpath);
		}

	}
	else if(isset($_GET['camera_id'])) {
		$image_url = '';
		$session_key = 'image_url_'.$_GET['camera_id'];
		if(!isset($_SESSION[$session_key])) {
			$json_file = $path.$_GET['camera_id'].'/camera.json';
			$result['camera'] = array();
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$camera_data->id = $_GET['camera_id'];
				$image_url = $camera_data->real_image_url;
				$_SESSION[$session_key] = $image_url;
			}
		}
		else {
			$image_url = $_SESSION[$session_key];
		}
		header("Content-Type: image/jpeg");
		echo file_get_contents($image_url);
	}
?>