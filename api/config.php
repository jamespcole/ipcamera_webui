<?
	define('DATA_PATH', realpath(dirname(__FILE__).'/../data'));
	define('CAMERA_DATA_PATH', realpath(dirname(__FILE__).'/../cameras'));
	define('USER_DATA_PATH', DATA_PATH.'/user.json');

	define('FIRST_RUN', !file_exists(USER_DATA_PATH));
?>