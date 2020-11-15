<?php

require_once(dirname(__DIR__, 2) . "/autoloader.php");

$db = new Database();
function startRotation($rotation) {
	global $db;
	$db->query("insert into priest_dc set rotation='{$rotation}'");
}

function getBuffList() {
	$firstState = [
		"spells" => [
			4 => 2,
			5 => 99
		],
		"path" => [],
	];
	$length = 9;

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
			startRotation(implode(" ", $state["path"]));
			startRotation("8 " . implode(" ", $state["path"]));
			echo implode(" ", $state["path"]) . "<br>";
			if (count($state["path"]) <= $length) {
				array_push($stack, $state);
			}
		}
	} while (!empty($stack));
	return $return;
}

getBuffList();


