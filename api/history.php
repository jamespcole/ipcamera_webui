<?
	require_once('config.php');
	require_once('security.php');
	require_once('motion_functions.php');
	header('Content-Type: application/json');

	if($_SERVER['REQUEST_METHOD'] === 'GET') {
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
			$contents = scandir($history_dir);
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
					$event_data = array();
					$event_data['date'] = $item;
					$event_data['event_id'] = $event;
					$event_data['time'] = getEventTimeInfo($history_dir.'/'.$item.'/'.$event);
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

	function getEventTimeInfo($path) {
		$result = array();
		$result['start'] = '';
		$result['length'] = '';
		$result['end'] = '';
		$contents = glob($path.'/*.jpg');
		if(count($contents) > 0) {
			$result['start'] = filemtime($contents[0]);
			$result['end'] = filemtime($contents[count($contents) - 1]);
			$result['length'] = $result['end'] - $result['start'];
			//return pathinfo($contents[0], PATHINFO_FILENAME);
		}

		return $result;
	}

?>