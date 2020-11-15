<?php

require_once(__DIR__ . "/autoloader.php");

$db = new Database();
while (true) {
	$count = RedisManager::getInstance()->scard(RedisManager::FUTURE_ROTATION);
	if ($count < 1000) {
		echo "sleep\n";
		sleep(5);
		continue;
	}
	$insertValues = [];
	for ($i = 0; $i < 1000; $i++) {
		$insertValues[] = RedisManager::getInstance()->spop(RedisManager::FUTURE_ROTATION);
	}
	$db->query("insert ignore into priest_dc(rotation) values('" . implode("'), ('", $insertValues) . "')");

	echo "add: " . count($insertValues) . "\n";
}