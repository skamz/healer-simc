<?php

require_once(dirname(__DIR__, 2) . "/autoloader.php");

function startRotation($rotation) {
	echo "add: " . $rotation . "<br>\n";
	Database::getInstance()->query("insert into priest_dc set rotation='8 {$rotation}'");
}

function getBuffList() {
	$firstState = [
		"spells" => [
			4 => 0,
			5 => 99
		],
		"path" => [],
	];
	$length = 13;

	$stack = [$firstState];

	$return = [];
	do {
		$currentPath = array_pop($stack);
		foreach ($currentPath["spells"] as $spell => $allowCount) {
			if ($allowCount == 0) {
				continue;
			}
			$state = $currentPath;
			$state["path"][] = $spell;
			$state["spells"][$spell]--;
			startRotation(implode(" ", $state["path"]) . " 4 4");
			if (count($state["path"]) <= $length) {
				array_push($stack, $state);
			}
		}
	} while (!empty($stack));
	return $return;
}

getBuffList();


