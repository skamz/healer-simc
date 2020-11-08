<?php

$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
$script = "F:/OpenServer/domains/wow.ru/rotation_checker.php";

$command = "{$phpPath} {$script}";

while (true) {
	$out = "";
	exec($command, $out);
	if (count($out) < 5) {
		break;
	}
	echo ($counter++) . " ";
}

