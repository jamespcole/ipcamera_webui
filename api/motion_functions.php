<?
	function loadMotionData($motion_id) {
		$path = MOTION_DATA_PATH.'/'.$motion_id.'/server.json';
		$data = file_get_contents($path);
		return json_decode($data);
	}

	function getMotionUrl($motion_data) {
		$url = $motion_data->protocol.'://';
		if($motion_data->username != '' && $motion_data->password != '') {
			$url .= $motion_data->username.':'.$motion_data->password.'@';
		}
		$port = ($motion_data->port);
		$url .=  $motion_data->server.':'.$port.'/';
		return $url;
	}

	function getThreadProperties($motion_data, $thread_number) {
		$url = getMotionUrl($motion_data).$thread_number.'/config/list';
		$data = file_get_contents($url);
		$vars = explode("\n", $data);
		$thread_data = array();
		foreach($vars as $pair) {
			$keyval = explode('=', $pair);
			if(count($keyval) > 1) {
				$val = (trim($keyval[1]) == '(null)') ? NULL : trim($keyval[1]);
				$thread_data[trim($keyval[0])] = $val;
			}
		}
		return $thread_data;
	}

	function setConfigValue($motion_data, $thread_number, $key, $value) {
		$url = getMotionUrl($motion_data).$thread_number.'/config/set?'.$key.'='.$value;
		$data = file_get_contents($url);
		return $data;
	}

	function writeConfigValue($motion_data, $thread_number) {
		//$url = getMotionUrl($motion_data).'0/config/set?control_html_output=on';
		//$data = file_get_contents($url);
		$url = getMotionUrl($motion_data).$thread_number.'/config/writeyes';
		$data = file_get_contents($url);
		return $data;
	}

	function checkMainConfigWriteable() {
		if(!is_writable('/etc/motion/motion.conf')) {
			return FALSE;
		}
		return TRUE;
	}

?>