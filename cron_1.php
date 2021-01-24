<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

const INC_AMOUNT = 100;

function getHealAmount($data) {
	return $data["atonement"];
}

function getCommand($rotation, $type, $incAmount = INC_AMOUNT) {
	$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
	$script = __DIR__ . "/rotation_checker_stat.php";

	$command = "{$phpPath} {$script}";
	$command .= ' --rotation="' . $rotation . '"';
	switch ($type) {
		case "base":
			break;

		case "int":
			$command .= ' --int="' . (Player::getInstance()->getInt() + $incAmount) . '"';
			break;

		case "crit":
			$command .= ' --crit="' . (Player::getInstance()->getCritCount() + $incAmount) . '"';
			break;

		case "haste":
			$command .= ' --haste="' . (Player::getInstance()->getHasteCount() + $incAmount) . '"';
			break;

		case "mastery":
			$command .= ' --mastery="' . (Player::getInstance()->getMasteryCount() + $incAmount) . '"';
			break;

		case "versa":
			$command .= ' --versa="' . (Player::getInstance()->getVersatilityCount() + $incAmount) . '"';
			break;

		default:
			throw new Exception("getCommand unknown type: {$type}");
	}
	return $command;
}

function getRotation($rotation, $iterations = 1000) {
	$calcTypes = ["base", "crit", "haste", "versa", "mastery"];
	$typeAvg = [];
	foreach ($calcTypes as $type) {
		$execCommand = getCommand($rotation, $type);
		echo "ExecCommand: {$execCommand}\n";

		$summary = [];
		for ($iter = 0; $iter < $iterations; $iter++) {
			echo round($iter / $iterations * 100) . " % \r";
			$out = [];

			exec($execCommand, $out);
			$out = array_filter($out);
			if (count($out) > 1) {
				throw new Exception("Stat script in debug mode");
			}
			$jsonData = array_shift($out);
			$healAmount = getHealAmount(json_decode($jsonData, true));
			$summary[] = $healAmount;

		}
		$typeAvg[$type] = array_sum($summary) / count($summary);
	}
	return $typeAvg;
}

function analyze($rotationsData) {
	$maxInfo = [
		"rotation" => "",
		"amount" => 0,
	];
	foreach ($rotationsData as $rotation => $statResults) {
		foreach ($statResults as $type => $amount) {
			if ($amount > $maxInfo["amount"]) {
				$maxInfo = [
					"type" => $type,
					"rotation" => $rotation,
					"amount" => $amount,
				];
			}
		}
	}

}

function analyzeOnceRotation(array $statInfo) {
	$incResults = [
		"int" => round(($statInfo["int"] - $statInfo["base"]) / INC_AMOUNT),
		"crit" => round(($statInfo["crit"] - $statInfo["base"]) / INC_AMOUNT),
		"haste" => round(($statInfo["haste"] - $statInfo["base"]) / INC_AMOUNT),
		"mastery" => round(($statInfo["mastery"] - $statInfo["base"]) / INC_AMOUNT),
		"versa" => round(($statInfo["versa"] - $statInfo["base"]) / INC_AMOUNT),
	];
	$incResults = Helper::calcStatWeight($incResults);
	print_r($incResults);
}


$rotations = [
	//"8 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	"8 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
	//"8 5 5 5 5 5 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3",
];


$totalData = [];
foreach ($rotations as $rotation) {
	echo "Start rotation: {$rotation}\n";
	$totalData[$rotation] = getRotation($rotation);
	if (count($rotations) == 1) {
		analyzeOnceRotation($totalData[$rotation]);
	}

}
