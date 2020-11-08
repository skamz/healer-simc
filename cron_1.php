<?php

$phpPath = "php";
$script = __DIR__ . "/rotation_checker.php";

$command = "{$phpPath} {$script}";

while (true) {
	$out = "";
	exec($command, $out);
	if (count($out) < 5) {
		break;
	}
	echo ($counter++) . " ";
}

