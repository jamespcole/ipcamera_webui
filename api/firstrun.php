<?
	require_once('config.php');
	header('Content-Type: application/json');

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if(isset($_GET['prerequisites_check'])) {
			$has_errors = FALSE;

			$results = array();
			$results['checks'] = array();

			$result = array();
			if(is_writable(DATA_PATH)) {
				$result['name'] = 'Data directory writeable';
				$result['result'] = 'success';
				$result['description'] = 'The data directory has the correct permissions';
				$result['resolution'] = '';
			}
			else {
				$has_errors = TRUE;
				$result['name'] = 'Data directory is not writeable';
				$result['result'] = 'danger';
				$result['description'] = 'The data directory does not have the correct permissions';
				$result['resolution'] = 'To fix this error you need to run the command "sudo chown -R www-data:www-data '.DATA_PATH.'" then "sudo chmod -R g+rw '.DATA_PATH.'"';
			}
			array_push($results['checks'], $result);

			$result = array();
			if(file_exists(BASE_PATH.'/.htaccess')) {
				$result['name'] = 'Found the .htaccess file';
				$result['result'] = 'success';
				$result['description'] = 'The .htaccess file was found in the correct location';
				$result['resolution'] = '';
			}
			else {
				$has_errors = TRUE;
				$result['name'] = 'Could not find the .htaccess file';
				$result['result'] = 'danger';
				$result['description'] = 'The .htaccess file was not found in the correct location';
				$result['resolution'] = 'The .htaccess file is hidden, if you copied the files here make sure that you turn on "Show hidden files" before copying';
			}
			array_push($results['checks'], $result);

			$result = array();
			if(in_array('mod_rewrite', apache_get_modules())) {
				$result['name'] = 'Mod Rewrite';
				$result['result'] = 'success';
				$result['description'] = 'Mod Rewrite is installed';
				$result['resolution'] = '';
			}
			else {
				$has_errors = TRUE;
				$result['name'] = 'Mod Rewrite';
				$result['result'] = 'danger';
				$result['description'] = 'Mod Rewrite is not installed on the server';
				$result['resolution'] = 'To install mod rewrite run the command "sudo a2enmod rewrite".  You will also need to enable mod rewrite for the site.  For details check http://setupguides.blogspot.com.au/2013/04/install-lamp-in-ubuntu-1304.html';
			}
			array_push($results['checks'], $result);


			$results['has_errors'] = $has_errors;
			echo json_encode($results);
		}
	}
?>