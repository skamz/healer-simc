<?php

require_once(__DIR__ . "/autoloader.php");
include(__DIR__ . "/player.php");

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
			} else {
				echo "$type - Done\n";
			}
		}
		if (empty($waitData)) {
			break;
		} else {
			echo " Progress: " . implode("; ", $waitData)."       \r";
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
	print_r($statInfo);
	analyzeOnceRotation($statInfo);
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

function startProcesses($calcTypes) {
	foreach ($calcTypes as $type) {
		$command = 'setsid nohup /usr/bin/php /var/www/admin/data/www/wow-sim.ru/cron_one_stat.php --rotation="8 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3" --type=' . $type . ' > /dev/null &';
		exec($command);
	}
	sleep(10);
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


$calcTypes = ["base", "int", "crit", "haste", "versa", "mastery"];
startProcesses($calcTypes);
viewProgress($calcTypes);
getData($calcTypes);

// setsid nohup /usr/bin/php /var/www/admin/data/www/wow-sim.ru/cron_one_stat.php --rotation="8 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3" --type=crit > /dev/null &


