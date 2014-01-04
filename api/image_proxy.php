<?
	require_once('config.php');
	require_once('functions.php');
	//require_once('security.php');
	$path = CAMERA_DATA_PATH.'/';
	//for testing configuration before saving
	if(isset($_GET['configuration_test'])) {
		require_once('security.php');
		if(isset($_GET['test_url'])) {
			$image_url = $_GET['test_url'];
			$info = getImageInfo($image_url);
			if($info['type'] == 'MJPEG') {
				getSnapshot($info['boundary'], $image_url);
			}
			else {
				header('Content-Type: image/jpeg');
				readfile($image_url);
			}
		}

	}
	else if(isset($_GET['camera_id'])) {
		$log_id = $_GET['camera_id'].'-'.time();
		$image_url = '';
		$info = null;
		//You can't set session info for a stream so only
		//do the session caching if it is a snapshot
		$session_key = 'image_url_'.$_GET['camera_id'];
		$info_session_key = 'image_info_'.$_GET['camera_id'];
		$load_from_session = false;
		$load_info_from_session = false;
		if(isset($_GET['snapshot']) && $_GET['snapshot'] == 'true') {
			if(!isset($_SESSION)) {
				session_start();
			}
			if(isset($_SESSION[$session_key])) {
				$load_from_session = true;
			}
			if(isset($_SESSION[$info_session_key])) {
				$load_info_from_session = true;
			}
		}
		/*if(!isset($_SESSION[$session_key])) {*/
		if(!$load_from_session) {
			$json_file = $path.$_GET['camera_id'].'/camera.json';
			$result['camera'] = array();
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$camera_data->id = $_GET['camera_id'];
				$image_url = $camera_data->real_image_url;

				if(isset($_SESSION)) {
					$_SESSION[$session_key] = $image_url;
				}
			}
		}
		else {
			$image_url = $_SESSION[$session_key];
		}

		if(!$load_info_from_session) {
			$info = getImageInfo($image_url);
			if(isset($_SESSION)) {
				$_SESSION[$info_session_key] = $info;
			}
		}
		else {
			$info = $_SESSION[$info_session_key];
		}

		set_time_limit(10);
		ignore_user_abort(false);
		register_shutdown_function('stopScript', $log_id);

		if(isset($_GET['snapshot']) && $_GET['snapshot'] == 'true') {
			if($info['type'] == 'MJPEG') {
				log_info($log_id.' Started snapshot from MJPEG stream.  '.$image_url);
				getSnapshot($info['boundary'], $image_url);
			}
			else {
				log_info($log_id.' Started snapshot from JPEG.  '.$image_url);
				header('Content-Type: image/jpeg');
				readfile($image_url);
			}
		}
		else {
			if($info['type'] == 'MJPEG') {
				log_info($log_id.' Started stream from MJPEG.  '.$image_url);
				ob_start();
				/*$headers = get_headers($image_url);
				foreach($headers as $header) {
					header($header);
				}*/
				header('Content-type: multipart/x-mixed-replace;boundary='.$info['boundary']);
				//readfile($image_url);
				$f = fopen($image_url, 'rb');
				$chunk = '';
				while($chunk = fread($f, 1024)) {
					echo $chunk;
					ob_flush();
        			flush();
					if(connection_aborted()) {
						fclose($f);
						break;
					}
				}
				ob_end_flush();
			}
			else if($info['type'] == 'JPEG') {
				log_info($log_id.' Started stream from JPEG.  '.$image_url);
				$boundary = '--ipcamera--';


				header('Content-type: multipart/x-mixed-replace;boundary='.$boundary);

				echo $boundary."\r\n";
				$count = 0;
				while($count < 10000000) {
					$img = file_get_contents($image_url);
					echo "Content-type: image/jpeg\r\n";
					echo 'Content-Length: '.strlen($img)."\r\n\r\n";
					echo $img."\r\n";
					echo $boundary."\r\n";
					if(connection_aborted()) {
						break;
					}
					$count++;
					//ob_flush();
				}
			}
		}
		log_info($log_id.' Finished.');
		die();

	}

	function getSnapshot($boundary, $url) {
		//$boundary="\n--";
		$f = fopen($url,"r") ;
		if(!$f) {
			echo "error";
		}
		else {
			$r = '';
			//**** URL OK
			while (substr_count(strtolower($r),"content-length") != 2 && strlen($r < 100000)) $r.=fread($f,35000);
			//$r.=fread($f,100);
			//echo $r;
			$start = strpos($r,"\xff");
			//echo $start;
			//die();
			$end   = strpos($r,$boundary,$start)-1;
			$frame = substr("$r",$start,$end - $start);

			header("Content-type: image/jpeg");
			echo $frame;
		}
		fclose($f);
	}

	function getImageInfo($url) {
		$headers = get_headers($url, 1);
		$result = array();
		$result['type'] = '';
		$result['boundary'] = '';

		$headers = array_change_key_case($headers, CASE_LOWER);

		if(isset($headers['content-type'])) {
			$content_type = $headers['content-type'];
			$ctype_bits = explode(';', $content_type);
			if(count($ctype_bits) > 0) {
				if(trim($ctype_bits[0]) == 'multipart/x-mixed-replace') {
					$result['type'] = 'MJPEG';
					foreach($ctype_bits as $ctype_bit) {
						$ctype_bit = trim($ctype_bit);
						if(strpos($ctype_bit, 'boundary=') === 0) {
							$boundary_parts = explode('=', $ctype_bit);
							if(count($boundary_parts) > 1) {
								$result['boundary'] = trim($boundary_parts[1]);
								if(strpos($result['boundary'], '--') !== 0) {
									$result['boundary'] = '--'.$result['boundary'];
								}
							}
						}
					}
				}
				else if($headers['content-type'] == 'image/jpeg') {
					$result['type'] = 'JPEG';
				}
			}
		}
		return $result;

	}

	function stopScript($params) {
		log_info($params.' Aborted.');
		/*$myFile = "/tmp/cam-".time().'-'.$params.".txt";
	    $handle = fopen($myFile,'a');
	    fwrite($handle,"test\n");
	    fclose($handle);
	    chmod($myFile, 0775);*/
	    exit();
	}
?>