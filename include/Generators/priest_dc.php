<?php

require_once(dirname(__DIR__, 2) . "/autoloader.php");

function startRotation($rotation) {
	echo "add: " . $rotation . "<br>\n";
	Database::getInstance()->query("insert ignore into priest_dc set rotation='{$rotation}'");
}

function fillStates($startPath) {
	$firstState = [
		"spells" => [
			17 => 1,
			13 => 1,
			14 => 1,
			11 => 0,
			4 => 2,
		],
		"path" => [$startPath],
	];
	$stack = [$firstState];
	do {
		$currentPath = array_pop($stack);
		foreach ($currentPath["spells"] as $spell => $allowCount) {
			if ($allowCount == 0) {
				continue;
			}
			$state = $currentPath;
			$state["path"][] = $spell;
			$state["spells"][$spell]--;
			startRotation(implode(" ", $state["path"]));
			array_push($stack, $state);
		}
	} while (!empty($stack));
}

function getBuffList() {
	$firstState = [
		"spells" => [
			4 => 0,
			5 => 10,
		],
		"path" => [],
	];
	$length = 11;

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

			fillStates(implode(" ", $state["path"]));

			if (count($state["path"]) <= $length) {
				array_push($stack, $state);
			}
		}
	} while (!empty($stack));
	return $return;
}

getBuffList();


