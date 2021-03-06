<?php

error_reporting(E_ALL & ~E_NOTICE);

spl_autoload_register(function($className) {
	$includePath = strtr($className, [
		"\\" => "/",
	]);
	require_once(__DIR__ . "/include/{$includePath}.php");
});

if (empty($argv) || !in_array("manual", $argv)) {
	//exit("Stop by autoloader");
}