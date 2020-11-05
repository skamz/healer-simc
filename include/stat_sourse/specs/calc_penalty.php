<?php

$spec = $_GET["spec"];
if (empty($spec)) {
	throw new Exception("GET spec не указан");
}

function getBaseValue($sourceRow) {
	echo "===========getBaseValue=========\n";
	$values = [];
	foreach ($sourceRow as $row) {
		list($value, $percent) = explode(";", $row);
		$values[$value] = $percent;
	}
	ksort($values);
	print_r($values);

	$allowDiff = 0.005;
	$prev = false;
	$blocks = [];
	$blockNum = 0;
	$penaltyPercents = [
		0 => [
			"stat" => 0,
			"percent" => 0
		],
		1 => [
			"stat" => 319,
			"percent" => 40.36
		],
	];
	foreach ($values as $value => $percent) {
		$value -= $penaltyPercents[$blockNum]["stat"];
		$percent -= $penaltyPercents[$blockNum]["percent"];


		$valPerPercent = $value / $percent;
		echo "valPerPercent: $valPerPercent\n";
		if (!empty($blocks)) {
			$avgBlock = array_sum($blocks[$blockNum]) / count($blocks[$blockNum]);
			echo "avgBlock: $avgBlock\n";
			$diff = abs($valPerPercent - $avgBlock);
			echo "diff: " . $diff . "\n";
			if ($diff > $allowDiff) {
				$blockNum++;
			}
		}
		if (empty($blocks[$blockNum])) {
			$blocks[$blockNum] = [];
		}

		$prev = $valPerPercent;
		$blocks[$blockNum][$percent] = $valPerPercent;
		echo "================\n";
	}
	print_r($blocks);


	$return = [];
}

$sourceRow = require_once(__DIR__ . "/{$spec}/mastery.php");

getBaseValue($sourceRow);

$valuePerPercent = [];
foreach ($sourceRow as $row) {
	list($value, $percent) = explode(";", $row);
	$valuePerPercent[] = $value / $percent;
}

print_r($valuePerPercent);