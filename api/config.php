<?
	define('BASE_PATH', realpath(dirname(__FILE__).'/..'));
	define('DATA_PATH', realpath(dirname(__FILE__).'/../data'));
	define('CAMERA_DATA_PATH', DATA_PATH.'/cameras');
	define('MOTION_DATA_PATH', DATA_PATH.'/motion_servers');
	define('USER_DATA_PATH', DATA_PATH.'/user.json');


	define('FIRST_RUN', !file_exists(USER_DATA_PATH));

	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
	//echo basename(__FILE__);

	$base_url = $protocol.$_SERVER['HTTP_HOST'].dirname(str_replace('api/', '', $_SERVER['PHP_SELF'])).'/';
	define('BASE_URL', $base_url);
	//echo BASE_URL;
	//die();
?>