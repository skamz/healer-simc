<?php

require_once(__DIR__ . "/autoloader.php");

$db = new Database();
$emptyIteration = 0;
while (true) {
	$count = RedisManager::getInstance()->scard(RedisManager::FUTURE_ROTATION);
	$countInWork = RedisManager::getInstance()->scard(RedisManager::ROTATIONS);
	if ($count < 1000 && $countInWork > 0) {
		echo "sleep\n";
		sleep(5);
		continue;
	}
	if ($countInWork == 0 && $count == 0) {
		if ($emptyIteration++ > 10) {
			break;
		}
		sleep(10);
		continue;
	}
	$emptyIteration = 0;
	$insertValues = [];
	for ($i = 0; $i < 1000; $i++) {
		$insertValues[] = RedisManager::getInstance()->spop(RedisManager::FUTURE_ROTATION);
	}
	$insertValues = array_filter($insertValues);
	$db->query("insert ignore into priest_dc(rotation) values('" . implode("'), ('", $insertValues) . "')");

	echo "add: " . count($insertValues) . "\n";
}


