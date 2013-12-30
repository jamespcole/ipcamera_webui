<?
	require_once('config.php');
	require_once('security.php');
	require_once('motion_functions.php');


	if($_SERVER['REQUEST_METHOD'] === 'GET') {
		$action = '';
		if(isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		if($action === '') {
			header('Content-Type: application/json');
			$results = array();
			try {
				$camera_id = (!empty($_GET['camera_id'])) ? $_GET['camera_id'] : '';
				if(!$camera_id) {
					throw new Exception('No camera id was passed.');
				}
				if(!file_exists(CAMERA_DATA_PATH.'/'.$camera_id.'/camera.json')) {
					throw new Exception('The requested camera id does not exist.');
				}

				$results['dates'] = array();
				$results['events'] = array();
				$history_dir = CAMERA_DATA_PATH.'/'.$camera_id.'/history';
				$contents = scandir($history_dir, SCANDIR_SORT_DESCENDING);
				foreach($contents as $item) {
					if($item == '.' || $item == '..') {
						continue;
					}
					$result = array();
					$result['date'] = $item;
					array_push($results['dates'], $result);
					$events = scandir($history_dir.'/'.$item);
					foreach($events as $event) {
						if($event == '.' || $event == '..') {
							continue;
						}
						$event_data = getEventInfo($history_dir.'/'.$item.'/'.$event, $event, $item, $camera_id);
						$event_data['date'] = $item;
						$event_data['event_id'] = $event;
						$event_data['camera_id'] = $camera_id;
						//$event_data['time'] = getEventTimeInfo($history_dir.'/'.$item.'/'.$event);
						array_push($results['events'], $event_data);
					}


				}

			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);
		}
		else if($action == 'event_data') {
			header('Content-Type: application/json');
			$camera_id = $_GET['camera_id'];
			$date = $_GET['date'];
			$event_id = $_GET['event_id'];
			$results = array();
			try {
				$path = CAMERA_DATA_PATH.'/'.$camera_id.'/history/'.$date.'/'.$event_id;
				$results = getEventInfo($path, $event_id, $date, $camera_id, TRUE);
			}
			catch(Exception $ex) {
				$results['result'] = 'error';
				$results['message'] = $ex->getMessage();
				header("HTTP/1.1 403 Unauthorized" );
			}
			echo json_encode($results);

		}
		else if($action == 'get_image') {
			$path = CAMERA_DATA_PATH.'/'.$_GET['camera_id'].'/history/'.$_GET['date'].'/'.$_GET['event_id'].'/'.$_GET['filename'];
			header("Content-Type: image/jpeg");
			echo file_get_contents($path);
		}

	}

	function getEventInfo($path, $event_id, $date, $camera_id, $include_images=FALSE) {
		$result = array();
		$result['start'] = '';
		$result['length'] = '';
		$result['end'] = '';
		$result['filename'] = '';
		$result['preview'] = '';
		$contents = glob($path.'/*.jpg');
		if(count($contents) > 0) {
			$result['start'] = filemtime($contents[0]);
			$result['end'] = filemtime($contents[count($contents) - 1]);
			$result['length'] = $result['end'] - $result['start'];
			$result['filename'] = pathinfo($contents[0], PATHINFO_FILENAME).'.'.pathinfo($contents[0], PATHINFO_EXTENSION);
			$half_way = floor(count($contents) / 2);
			if($half_way < count($contents)) {
				$result['preview'] = 'api/history.php?action=get_image&event_id='.$event_id.'&date='.$date.'&camera_id='.$camera_id.'&filename='.pathinfo($contents[$half_way], PATHINFO_FILENAME).'.'.pathinfo($contents[$half_way], PATHINFO_EXTENSION);
			}
			if($include_images) {
				$result['images'] = array();
				foreach($contents as $event_image) {
					$image_data = array();
					$image_data['time'] = filemtime($event_image);
					$image_data['image_url'] = 'api/history.php?action=get_image&event_id='.$event_id.'&date='.$date.'&camera_id='.$camera_id.'&filename='.pathinfo($event_image, PATHINFO_FILENAME).'.'.pathinfo($event_image, PATHINFO_EXTENSION);
					array_push($result['images'], $image_data);
				}
			}
			//return pathinfo($contents[0], PATHINFO_FILENAME);
		}

		return $result;
	}

?>