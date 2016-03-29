<?php
function debug($data) {
    echo '<pre>'. print_r($data, true) .'</pre>';
}

function s ($site='') {
    return $site?($GLOBALS['S_SITE']=$site):$GLOBALS['S_SITE'];
}

function t ($t='') {
    return $t?($GLOBALS['S_TITLE']=$t):$GLOBALS['S_TITLE'];
}

function c ($c='') {
    return $c?($GLOBALS['S_CONTENT']=$c):$GLOBALS['S_CONTENT'];
}

function f () {
    return $_SERVER['DOCUMENT_ROOT'].$GLOBALS['S_SUBPATH'];
}

function p ($f='',$s='') {
    $add = $f ? '/'.($s?$s.'/'.$f:uri(0).'/'.$f) : '';
	return 'http://'.$_SERVER['SERVER_NAME'].$GLOBALS['S_SUBPATH'].$add;
}

function uri ($nr=0) {
	return isset($GLOBALS['S_URI_PARTS'][$nr]) ? $GLOBALS['S_URI_PARTS'][$nr] : '';
}

function get($name, $default = '', $get = true, $post = true) {
	return $post && isset($_POST[$name]) ? $_POST[$name] : ($get && isset($_GET[$name]) ? $_GET[$name] : $default);
}

function urlPath($html_encode = true) {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $html_encode ? htmlentities($url, ENT_QUOTES) : $url;
}
