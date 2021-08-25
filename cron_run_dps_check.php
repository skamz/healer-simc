<?php

$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
if (!file_exists(dirname($phpPath))) {
	$phpPath = "php";
}

$script = __DIR__ . "/dps_rotations_checker.php";

$command = "{$phpPath} {$script}";
$command = 'bash -c ' . escapeshellarg('exec nohup setsid ' . $command) . ' 2>&1 &';
while (true) {
	$out = "";
	exec($command, $out);
	echo $i++ . ", ";
}
