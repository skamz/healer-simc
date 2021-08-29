<?php
require_once(__DIR__ . "/autoloader.php");

function isAllowFill() {
	$key = RedisManager::KEY_FILL_WORK_LOCK;
	$isLock = RedisManager::getInstance()->get($key);
	if (empty($isLock)) {
		return true;
	}
	return false;
}

function resetLock() {
	RedisManager::getInstance()->del(RedisManager::KEY_FILL_WORK_LOCK);
}

function fillWork() {
	if (!isAllowFill()) {
		return false;
	}

	$sql = "insert ignore into priest_dc_work 
		select * from priest_dc";
	Database::getInstance()->query($sql);

	$count = getCountInWork();

	if ($count > 50000) {
		RedisManager::getInstance()->setex(RedisManager::KEY_FILL_WORK_LOCK, 1, Helper::ONE_HOUR);
	}

	echo "fillWork\n";
}

function getTopValue() {
	$key = RedisManager::AVG_RESULT_WORK_LOCK;
	$lockValue = RedisManager::getInstance()->get($key);
	if (!empty($lockValue)) {
		echo "Lock value: {$lockValue}\n";
		return $lockValue;
	}

	$sql = "select avg(dmg) from (
    			select total_heal/iterations as dmg 
    			from priest_dc_work 
    			where iterations>0
    			order by dmg desc
    			limit 100
   			 ) as t";
	$data = Database::getInstance()->query($sql)->fetchArray();
	$return = array_shift($data);
	RedisManager::getInstance()->setex($key, $return, Helper::ONE_MINUTE * 5);
	return $return;
}

function getCountInWork() {
	$sql = "select count(*) from priest_dc_work";
	$return = Database::getInstance()->query($sql)->fetchArray();
	return array_shift($return);
}

function getMaxWorkIterations() {
	$sql = "SELECT max(iterations) as avgIt FROM priest_dc_work";
	$avgInfo = Database::getInstance()->query($sql)->fetchArray();
	return $avgInfo["avgIt"];
}

function deleteLowData() {
	$countInWork = getCountInWork();
	if ($countInWork < 100) {
		return true;
	}

	$topValue = getTopValue();
	$cleanBy = intval($topValue * 0.7);
	deleteRotationByValue($cleanBy);
}

function removeRow(int $id) {
	$sql = "delete from priest_dc where id={$id}";
	Database::getInstance()->query($sql);

	$sql = "delete from priest_dc_work where id={$id}";
	Database::getInstance()->query($sql);
}

function deleteRotationByValue(int $cleanBy) {
	$sql = "SELECT id FROM priest_dc_work WHERE iterations>0 and total_heal/iterations>{$cleanBy} limit 1000";
	$rows = Database::getInstance()->query($sql)->fetchAll();
	foreach ($rows as $row) {
		removeRow($row["id"]);
	}
}

function getCheckRotations(int $iterations, int $avgFilter) {

}

$db = new Database();

$insertByStep = 5000;
$sleepCounter = 0;

RedisManager::getInstance()->set(RedisManager::RUN_ROTATION_COUNT, 10);

while (true) {
	fillWork();
	$countInList = RedisManager::getInstance()->scard(RedisManager::ROTATIONS);
	if (empty($countInList)) {
		resetLock();
	}
	echo "lost count: " . $countInList;
	if ($countInList > $insertByStep / 2) {
		echo " - sleep\n";
		$sleepCounter++;
		if ($sleepCounter >= 500) {
			break;
		}
		sleep(1);
		continue;
	}
	$sleepCounter = 0;
	echo " - add\n";

	//@todo кривой код, переделать на getCheckRotations
	$rotationRows = $db->query("select id from priest_dc_work where iterations=0 limit {$insertByStep}")->fetchAll();
	if (empty($rotationRows)) {
		$topValue = getTopValue();

		RedisManager::getInstance()->set(RedisManager::RUN_ROTATION_COUNT, 100);

		$cleanBy = intval($topValue * 0.85);
		$rotationRows = $db->query("select id from priest_dc_work where iterations<100 and total_heal/iterations>{$cleanBy} limit {$insertByStep}")->fetchAll();
		if (empty($rotationRows)) {
			$cleanBy = intval($topValue * 0.95);
			$rotationRows = $db->query("select id from priest_dc_work where iterations<10000 and total_heal/iterations>{$cleanBy} limit {$insertByStep}")->fetchAll();
			if (empty($rotationRows)) {

				continue;
			}
		}

	}

	foreach ($rotationRows as $row) {
		RedisManager::getInstance()->sadd(RedisManager::ROTATIONS, $row["id"]);
	}
	echo "add count: " . count($rotationRows) . "\n";
	if (count($rotationRows) < $insertByStep) {
		echo "Близко ко концу (или началу?), speel 10 sec\n";
		resetLock();
		sleep(10);
	}
}