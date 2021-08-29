<?php

require_once(__DIR__ . "/autoloader.php");
include(__DIR__ . "/player.php");

function printFormatedStat(array $calcTypes) {
	$statInfo = getData($calcTypes);
	$stats = analyzeOnceRotation($statInfo);
	$return = [];
	foreach ($stats as $name => $stat) {
		$return[] = "{$name}: $stat";
	}
	return implode(", ", $return);
}

function printInfo($infoStr) {
	file_put_contents("php://stdout", $infoStr, 8);
	error_log($infoStr);
	echo $infoStr . "\n";

}

function viewProgress(array $calcTypes) {
	$redisKeys = [];
	foreach ($calcTypes as $type) {
		$redisKeys[$type] = RedisManager::getStatCalcKeys($type);
	}
	$redis = RedisManager::getInstance();
	$wait = true;
	while (true) {
		$waitData = [];
		foreach ($redisKeys as $type => $redisTypeKeys) {
			$progress = $redis->get($redisTypeKeys["progress"]);
			if ($progress < 100) {
				$waitData[] = "{$type} ({$progress}%)";
			}
		}
		if (empty($waitData)) {
			break;
		} else {
			printInfo(" Progress: " . implode("; ", $waitData) . ". " . printFormatedStat($calcTypes));
			sleep(10);
		}
	}
	sleep(10);
}

function getData(array $calcTypes) {
	$redisKeys = [];
	foreach ($calcTypes as $type) {
		$redisKeys[$type] = RedisManager::getStatCalcKeys($type);
	}
	$redis = RedisManager::getInstance();
	$statInfo = [];
	foreach ($redisKeys as $type => $redisTypeKeys) {
		$statInfo[$type] = $redis->get($redisTypeKeys["data"]);
	}
	return $statInfo;
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
	return $incResults;
}

function startProcesses($calcTypes, $rotation) {
	$dir = __DIR__;
	foreach ($calcTypes as $type) {
		$command = 'setsid nohup php ' . $dir . '/cron_one_stat.php --rotation="' . $rotation . '" --type=' . $type . ' > /dev/null &';
		printInfo("run: " . $command);
		exec($command);
	}
	sleep(10);
}


$args = getopt("", ["type:", "rotation:"]);
if (empty($args["rotation"])) {
	throw new Exception("Rotation no set");
}
$analyzeRotation = trim($args["rotation"]);

$calcTypes = ["base", "int", "crit", "haste", "versa", "mastery"];
if ($args["type"] == "start") {
	echo "INC_AMOUNT: " . INC_AMOUNT . "\n";
	startProcesses($calcTypes, $analyzeRotation);
}
viewProgress($calcTypes);
$statInfo = getData($calcTypes);
print_r($statInfo);
print_r(analyzeOnceRotation($statInfo));

// setsid nohup /usr/bin/php /var/www/admin/data/www/wow-sim.ru/cron_one_stat.php --rotation="8 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3" --type=crit > /dev/null &


