<?
	session_start();
	if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE) {
		header('Content-Type: application/json');
		header("HTTP/1.1 401 Unauthorized" );
		$result = array();
		$result['result'] = 'error';
		$result['message'] = 'You are not logged in.';
		echo json_encode($result);
		die();
	}
?>