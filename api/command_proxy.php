<?
	require_once('security.php');
	if(isset($_GET['command_url'])) {
		echo file_get_contents($_GET['command_url']);
	}
?>