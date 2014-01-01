<?
	require_once('config.php');
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
			/*if(!empty($_GET['is_stream']) && $_GET['is_stream'] == 'true') //to get still from motion stream
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
			}*/
		}

	}
	else if(isset($_GET['camera_id'])) {
		//echo $_GET['snapshot'];
		//die();
		$image_url = '';
		$session_key = 'image_url_'.$_GET['camera_id'];
		//if(!isset($_SESSION[$session_key])) {
			$json_file = $path.$_GET['camera_id'].'/camera.json';
			$result['camera'] = array();
			if(file_exists($json_file)) {
				$camera_data = json_decode(file_get_contents($json_file));
				$camera_data->id = $_GET['camera_id'];
				$image_url = $camera_data->real_image_url;
				//$_SESSION[$session_key] = $image_url;
			}
		//}
		//else {
		//	$image_url = $_SESSION[$session_key];
		//}
		/*header("Content-Type: image/jpeg");
		echo file_get_contents($image_url);*/
		//$headers = get_headers($image_url, 1);
		//header("HTTP/1.1 200 OK" );
		//header("Connection: close");
		/*if(isset($headers['Content-Type'])) {
			header('Content-Type: '.$headers['Content-Type']);
		}
		if(isset($headers['content-length'])) {
			header('content-length: '.$headers['content-length']);
		}*/
		//set_time_limit(15);
		ignore_user_abort(false);
		register_shutdown_function('stopScript', $_GET['camera_id']);
		//echo $_GET['snapshot'];
		//die();
		//echo $image_url;
		$info = getImageInfo($image_url);
		//print_r($info);
		//die();
		if(isset($_GET['snapshot']) && $_GET['snapshot'] == 'true') {
			if($info['type'] == 'MJPEG') {
				getSnapshot($info['boundary'], $image_url);
			}
			else {
				header('Content-Type: image/jpeg');
				readfile($image_url);
			}
		}
		else {
			/*$headers = get_headers($image_url);
			foreach($headers as $header) {
				header($header);
			}*/

			if($info['type'] == 'MJPEG') {
				/*$headers = get_headers($image_url, 1);
				print_r($headers);
				die();
				if(isset($headers['Content-Type'])) {
					header('Content-Type: '.$headers['Content-Type']);
				}*/
				$headers = get_headers($image_url);
				foreach($headers as $header) {
					header($header);
				}
				readfile($image_url);
			}
			else if($info['type'] == 'JPEG') {
				$boundary = '--ipcamera--';

				//if(isset($headers['Content-Type'])) {
					header('Content-type: multipart/x-mixed-replace;boundary='.$boundary);
				//}
				echo $boundary."\r\n";
				while(true) {
					$img = file_get_contents($image_url);
					echo "Content-type: image/jpeg\r\n";
					echo 'Content-Length: '.strlen($img)."\r\n\r\n";
					echo $img."\r\n";
					echo $boundary."\r\n";
					ob_flush();
				}

			}

			//print_r($info);



			/*$f = fopen($image_url,"r") ;
			$r = '';
			$c = 0;
			while($c < 1000) {
				$r = fread($f,512);
				echo $r;
				ob_flush();
				$c++;
			}*/
			/*$info = getImageInfo($image_url);
			$boundary = $info['boundary'];
			$f = fopen($image_url,"r") ;
			$r = '';
			$c = 0;
			while($c < 100) {
				while (substr_count(strtolower($r),"content-length") != 2 && strlen($r < 50000)) $r.=fread($f,512);
				//$r.=fread($f,100);
				//echo $r;
				$start = strpos($r,"\xff");
				//echo $start;
				//die();
				$end   = strpos($r,$boundary,$start)-1;
				$frame = substr("$r",$start,$end - $start);
				//echo $image_url;
				$headers = get_headers($image_url);
				print_r($headers);
				foreach($headers as $header) {
					header($header);
				}
				echo $boundary."\n";
				echo $frame;
				echo $boundary."\n";
				ob_flush();
				$c++;
			}*/

		}
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
			while (substr_count(strtolower($r),"content-length") != 2 && strlen($r < 50000)) $r.=fread($f,512);
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
			/*$myFile = "/tmp/".time().'-'.$params.".txt";
		    $handle = fopen($myFile,'a');
		    fwrite($handle,"test\n");
		    fclose($handle);*/
		    exit();
	}
?>