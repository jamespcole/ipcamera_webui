<?
	require_once('config.php');
	require_once('security.php');
	$path = CAMERA_DATA_PATH.'/';
	//for testing configuration before saving
	if(isset($_GET['configuration_test'])) {
		if(isset($_GET['test_url'])) {
			if(!empty($_GET['is_stream']) && $_GET['is_stream'] == 'true') //to get still from motion stream
			{
				$boundary="\n--";
				$f = fopen($_GET['test_url'],"r") ;
				if(!$f) {
					echo "error";
				}
				else {
					$r = '';
					//**** URL OK
					while (substr_count($r,"Content-Length") != 2) $r.=fread($f,512);

					$start = strpos($r,"\xff");
					$end   = strpos($r,$boundary,$start)-1;
					$frame = substr("$r",$start,$end - $start);

					header("Content-type: image/jpeg");
					echo $frame;
				}
				fclose($f);
				die();
			}
			else {
				$realpath = $_GET['test_url'];
				header("Content-Type: image/jpeg");
				echo file_get_contents($realpath);
			}
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