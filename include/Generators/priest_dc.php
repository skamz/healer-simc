<?php

set_time_limit(0);

function isExists(Database $db, $rotation) {
	$row = $db->query("select id from priest_dc where rotation='{$rotation}'")->fetchArray();
	if (empty($row)) {
		return false;
	}
	return true;
}

$db = new Database();

$spellList = ["smite", "penance", "schism", "radiance", "shield"];
$length = 9;

$paths = [];
$stack = $spellList;

do {
	$currentPath = array_pop($stack);
	foreach ($spellList as $spell) {
		$path = "{$currentPath} {$spell}";
		if (substr_count($path, " ") == $length) {
			if (!isExists($db, $path)) {
				$db->query("insert into priest_dc set rotation='{$path}'");
			}
		} else {
			array_push($stack, $path);
		}
	}
} while (!empty($stack));

