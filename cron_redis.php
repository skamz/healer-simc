<?php

require_once(__DIR__ . "/autoloader.php");

function fillWork(Database $db, $insertByStep = 5000) {
	$sql = "select count(*) as cnt from priest_dc_work";
	$countInfo = $db->query($sql)->fetchArray();
	echo "in priest_dc_work: " . $countInfo["cnt"] . "\n";
	if ($countInfo["cnt"] > $insertByStep / 2) {
		return true;
	}

	$sql = "insert ignore into priest_dc_work 
		select * from priest_dc order by id desc limit {$insertByStep}";
	$db->query($sql);
	echo "add: " . $insertByStep . "\n";
}

$db = new Database();

$insertByStep = 1000;
$sleepCounter = 0;

while (true) {
	fillWork($db);
	$countInList = RedisManager::getInstance()->scard(RedisManager::ROTATIONS);
	echo "lost count: " . $countInList;
	if ($countInList > $insertByStep / 2) {
		echo " - sleep\n";
		$sleepCounter++;
		if ($sleepCounter >= 10) {
			break;
		}
		sleep(5);
		continue;
	}
	$sleepCounter = 0;
	echo " - add\n";

	$rotationRows = $db->query("select id from priest_dc_work order by id desc limit {$insertByStep}")->fetchAll();
	if (empty($rotationRows)) {
		continue;
	}

	foreach ($rotationRows as $row) {
		RedisManager::getInstance()->sadd(RedisManager::ROTATIONS, $row["id"]);
	}
	echo "add count: " . count($rotationRows) . "\n";
}