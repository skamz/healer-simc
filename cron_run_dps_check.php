<?php

$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
if (!file_exists(dirname($phpPath))) {
	$phpPath = "php";
}

$script = __DIR__ . "/dps_rotations_checker.php manual";

$command = "{$phpPath} {$script}";
while (true) {
	$out = "";
	exec($command, $out);
	echo $i++ . ", ";
}
