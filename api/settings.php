<?
	require_once('config.php');
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if(isset($_GET['prerequisites_check'])) {
			$result = array();
			if(is_writable(CAMERA_DATA_PATH)) {
				$result['name'] = 'Camera data directory writeable';
				$result['result'] = 'ok';
				$result['description'] = 'The camera data directory has the correct permissions';
				$result['resolution'] = '';
			}
			else {
				$result['name'] = 'Camera data directory is not writeable';
				$result['result'] = 'fail';
				$result['description'] = 'The camera data directory does not have the correct permissions';
				$result['resolution'] = 'To fix this error you need to run the command "chmod -R a+w '.CAMERA_DATA_PATH.'"';
			}
			echo json_encode($result);
		}
	}
?>