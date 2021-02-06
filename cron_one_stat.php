<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

$args = getopt("t:r", ["type:", "rotation:"]);

$redisKeys = RedisManager::getStatCalcKeys($args["type"]);
$redisStatJsonData = $redisKeys["data"];
$redisStatJsonProgress = "json_rotation_progress_{$args["type"]}";

RedisManager::getInstance()->set($redisStatJsonData, "-1");

function getHealAmount($data) {
	return $data["atonement"];
}

function getCommand($rotation, $type, $incAmount = INC_AMOUNT) {
	$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
	if (!file_exists(dirname($phpPath))) {
		$phpPath = "php";
	}

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

function getRotation($rotation, $type, $iterations = 100000) {
	global $redisStatJsonProgress, $redisStatJsonData;
	$calcTypes = ["base", "int", "crit", "haste", "versa", "mastery"];

	$execCommand = getCommand($rotation, $type);
	echo "ExecCommand: {$execCommand}\n";

	$summary = [];
	for ($iter = 0; $iter < $iterations; $iter++) {
		if ($iter % 100 == 0) {
			echo round($iter / $iterations * 100) . " % \r";
			RedisManager::getInstance()->set($redisStatJsonProgress, round($iter / $iterations * 100));
			$avg = round(array_sum($summary) / count($summary));
			RedisManager::getInstance()->set($redisStatJsonData, $avg);
		}

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

	return round(array_sum($summary) / count($summary));
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


print_R($args);

echo "Start rotation: {$args["rotation"]}\n";
$data = getRotation($args["rotation"], $args["type"]);
echo "Answer: " . $data . "\n";

RedisManager::getInstance()->set($redisStatJsonData, $data);

