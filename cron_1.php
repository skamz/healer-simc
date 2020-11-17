<?php

require_once(__DIR__ . "/autoloader.php");

$phpPath = "php";
$script = __DIR__ . "/rotation_checker.php";

$command = "{$phpPath} {$script}";
$emptyIteration = 0;

while (true) {
	$out = [];
	exec($command, $out);
	if (RedisManager::getInstance()->scard(RedisManager::ROTATIONS) < 1) {
		if ($emptyIteration++ > 10) {
			break;
		}
		sleep(5);
		continue;
	}
	$emptyIteration = 0;
	echo ($counter++) . " ";
}

