<?php
	error_reporting(E_ALL|E_STRICT);
	ob_start();
	define('S',1);
	define('ROOT', './');
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

	function debug($data) {
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	$files = file_list(getcwd().'/lib', '.php');
	foreach ($files as $file) {
		include './lib/'. $file;
	}

	$sessionPath = sys_get_temp_dir();
	session_save_path($sessionPath);
	session_start();

	$ini_array = parse_ini_file('./lib/config.ini');
	$default_timezone = $ini_array['time_zone'];
	if (!$default_timezone) {
		$default_timezone = 'Zulu';
	}
	date_default_timezone_set($default_timezone);

	$S_SUBPATH = str_replace('/index.php', '', $_SERVER['PHP_SELF']);
	$S_URI_PARTS = array_slice(explode('/', urldecode(trim(str_replace(array("\\", "\0", "\n", "\r", "<", ">", "\"", "'"), '', str_replace($S_SUBPATH, '', $_SERVER['REQUEST_URI']))))), 1);

	if (!defined('S_DEFAULT_SITEFILE')) define('S_DEFAULT_SITEFILE', 'Home');

	$S_URI_PARTS[0] = isset($S_URI_PARTS[0]) ? ($S_URI_PARTS[0]?$S_URI_PARTS[0]:S_DEFAULT_SITEFILE) : S_DEFAULT_SITEFILE;
	$S_URI_PARTS[1] = isset($S_URI_PARTS[1]) ? ($S_URI_PARTS[1]?$S_URI_PARTS[1]:'index') : 'index';

	$S_TITLE = $S_URI_PARTS[0];

	include file_exists('site/'. $S_URI_PARTS[0]. '.php') ? 'site/'. $S_URI_PARTS[0] . '.php' : 'site/404.php';

	if (class_exists($S_URI_PARTS[0])) {
		if (substr($S_URI_PARTS[1],0,1) == '_') {
			echo "Error: You can't call functions starting with '_' !!!<br />";
		}

		$S_SITE_OBJECT = new $S_URI_PARTS[0]();
		if (method_exists($S_URI_PARTS[0], $S_URI_PARTS[1])) {
			$S_SITE_OBJECT ->{$S_URI_PARTS[1]}();
		} else {
			$S_SITE_OBJECT -> index();
		}
	}

	$S_CONTENT = trim(ob_get_clean(), "\x20\x09\x0A\x0D\x00\x0B\xEF\xBB\xBF");

	if (isset($_GET['s_notpl']) || defined('s_notpl')) {
		die($S_CONTENT);
	} else {
		header('Content-type: text/html; charset=utf-8');
	}

	$keywords = $ini_array['keywords'];
	$description = $ini_array['description'];

	$debug = false;
	echo
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.
	'<html xmlns="http://www.w3.org/1999/xhtml">'.
	'<head>'.
		'<title>'. s() .' - '. t() .'</title>'.
        ( $keywords ? '<meta name="description" content="' . htmlentities($keywords) . '" />' : '').
        ( $description ? '<meta name="description" content="' . htmlentities($description) . '" />' : '').
		'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'.
        '<meta name="robots" content="all, index, follow" />' .
        '<meta name="author" content="AcmeAWS">'.
        '<meta name="copyright" content="AcmeAWS '. date('Y') .'">'.
        #'<meta name="email" content="support [at] acmeaws [dot] com">'.
        '<meta http-equiv="Content-Language" content="en">'.
        '<meta name="Charset" content="UTF-8">'.
        '<meta name="Rating" content="General">'.
        '<meta name="Distribution" content="Global">'.
        '<meta name="Robots" content="INDEX,FOLLOW">'.
        '<meta name="Revisit-after" content="7 Days">'.
        '<meta name="generator" content="Oceanside Trading 654 v 3.1 E" />' .
        '<link rel="shortcut icon" href="/assets/images/icon.ico" />'.
        '<link href="/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">'.
        '<link href="/assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">'.
        '<link href="/assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">'.
        '<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->'.
        '<!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->'.
        '<!--[if lt IE 9]>'.
        '<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>'.
        '<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>'.
        '<![endif]-->';
        $css = file_list(getcwd().'/assets/css', '.css');
        if(is_array($css)) {
        	$cssData = '';
        	foreach ($css as $style) {
        		if(isset($style)) {
        			$css_filename = './assets/css/'.$style;
        			if( !@$fh = fopen(realpath($css_filename), 'r')) {
        				die ('Could not open file: '. realpath($css_filename));
        			}
        			$cssData .= "\n". fread($fh, filesize($css_filename));
        			fclose($fh);
        		}
        	}
        }

        if(isset($cssData)) {
        	echo '<style>'.
        	"\n\n/* CSS Generated: ".date('r'). '*/'.
        	$cssData .
        	"\n\n/* CSS Generated: ".date('r'). '*/'.
        	'</style>';
        }

        echo
        '</head>'.
        '<body>'.
        '<div class="main-content">'.
        c();

        $js = file_list(getcwd().'/assets/js', '.js');
        if (is_array($js)) {
        	$jsData = '';
        	foreach ($js as $script) {
        		if (isset($script)) {
        			$jsFilename = './assets/js/'. $script;
        			if ( !@$fh = fopen(realpath($jsFilename), 'r')) {
        				die ('Could not open file '.realpath($jsFilename));
        			}
        			$jsData .= "\n" . fread($fh, filesize($jsFilename));
        			fclose($fh);
        		}
        	}
        }
        echo '
        <!-- jQuery -->
        <script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Metis Menu Plugin JavaScript -->
        <script src="/assets/bower_components/metisMenu/dist/metisMenu.min.js"></script>
        <!-- Morris Charts JavaScript -->
        <script src="/assets/bower_components/raphael/raphael-min.js"></script>
        <script src="/assets/bower_components/morrisjs/morris.min.js"></script>
        <script src="/assets/js/morris-data.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="/assets/dist/js/sb-admin-2.js"></script>';
        if (isset($jsData)) {
        	echo '<script type="text/javascript">'.
        	$jsData.
        	'</script>';
        }

        echo '</div>';

	if($debug) {
		echo
		'<div class="debug">'. debug($GLOBALS) .'</div>';
	}

	echo '</body></html>';
