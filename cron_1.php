<?php

$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
$script = "F:\OpenServer\domains\wow.ru\index.php";

$command = "{$phpPath} {$script}";

while (true) {
	$out = "";
	exec($command, $out);
	if (count($out) < 10) {
		break;
	}
	echo ($counter++) . " ";
}

