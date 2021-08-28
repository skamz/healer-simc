<?php
exit;
$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
if (!file_exists(dirname($phpPath))) {
	$phpPath = "php";
}

$script = __DIR__ . "/dps_rotations_checker.php";

$command = "{$phpPath} {$script}";
$errorCounter = 0;
while (true) {
	$out = "";
	$code = 0;
	exec($command, $out, $code);
	echo $i++ . " ({$code}), ";
	if ($code > 0) {
		sleep(1);
		if ($errorCounter++ > 50) {
			exit;
		}
	}
}
