<?php

define('EXT', '.php');

if (file_exists('install'.EXT)) {
	// Load the installation check
	return include 'install'.EXT;
}