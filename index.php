<?php
	error_reporting(E_ALL|E_STRICT);
	ob_start();
	define('S',1);
	define('ROOT', '.');
	if (file_exists('install.php')) {
		return include 'install.php';
	}
	
	function file_list($dir, $ext) {
		foreach (array_diff(scandir($dir), array('.', '..' )) as $file) if (is_file($dir . '/' . $file) && (($ext) ? @ereg($ext . '$', $file) : 1)) $list[] = $file;
		if (isset($list)) {
			return $list;
		} else {
			return '';
		}
	}
	
	$files = file_list(getcwd().'/lib', '.php');
	foreach ($files as $file) {
		include './lib/'. $file;
	}
	
	$sessionPath = sys_get_temp_dir();
	session_save_path($sessionPath);
	session_start();
	
	/*
	$default_timezone = $DB->lookup('SELECT d.value FROM domain d WHERE d.group="System" AND d.name="timezone"');
	if (!$default_timezone) {
		$default_timezone = 'Zulu';
	}
	*/
	
	$ini_array = parse_ini_file('./lib/config.ini');
	debug($ini_array);
	

	
?>