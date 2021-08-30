<?php

require_once(__DIR__ . "/autoloader.php");

$db = new Database();
$emptyIteration = 0;
while (true) {
	$count = RedisManager::getInstance()->scard(RedisManager::FUTURE_ROTATION);
	$countInWork = RedisManager::getInstance()->scard(RedisManager::ROTATIONS);
	if ($count < 10 && $countInWork > 0) {
		echo "sleep\n";
		sleep(1);
		continue;
	}
	if ($countInWork == 0 && $count == 0) {
		if ($emptyIteration++ > 100) {
			break;
		}
		sleep(1);
		continue;
	}
	$emptyIteration = 0;
	$insertValues = [];
	for ($i = 0; $i < 1000; $i++) {
		$rotation = RedisManager::getInstance()->spop(RedisManager::FUTURE_ROTATION);
		if (!empty($rotation)) {
			$insertValues[] = $rotation;
		} else {
			break;
		}
	}
	$insertValues = array_filter($insertValues);
	$sql = "insert ignore into priest_dc_work(rotation) values('" . implode("'), ('", $insertValues) . "')";
	echo $sql . "\n";
	$db->query($sql);

	echo "add: " . count($insertValues) . "\n";
}


