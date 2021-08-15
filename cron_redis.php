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

function fillWork() {
	if (!isAllowFill()) {
		return false;
	}

	$sql = "insert ignore into priest_dc_work 
		select * from priest_dc";
	Database::getInstance()->query($sql);

	RedisManager::getInstance()->setex(RedisManager::KEY_FILL_WORK_LOCK, 1, Helper::ONE_HOUR);
	echo "fillWork\n";
}

function getTopValue() {
	$sql = "select avg(dmg) from (
    			select total_heal/iterations as dmg 
    			from priest_dc_work 
    			where iterations>0
    			order by dmg desc
    			limit 100
   			 ) as t";
	$return = Database::getInstance()->query($sql)->fetchArray();
	return array_shift($return);
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

$insertByStep = 1000;
$sleepCounter = 0;

while (true) {
	fillWork();
	$countInList = RedisManager::getInstance()->scard(RedisManager::ROTATIONS);
	echo "lost count: " . $countInList;
	if ($countInList > $insertByStep / 2) {
		echo " - sleep\n";
		$sleepCounter++;
		if ($sleepCounter >= 100) {
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

		$cleanBy = intval($topValue * 0.7);
		$rotationRows = $db->query("select id from priest_dc_work where iterations<10 and total_heal/iterations>{$cleanBy} limit {$insertByStep}")->fetchAll();
		if (empty($rotationRows)) {
			$cleanBy = intval($topValue * 0.85);
			$rotationRows = $db->query("select id from priest_dc_work where iterations<100 and total_heal/iterations>{$cleanBy} limit {$insertByStep}")->fetchAll();
			if (empty($rotationRows)) {
				$cleanBy = intval($topValue * 0.95);
				$rotationRows = $db->query("select id from priest_dc_work where iterations<1000 and total_heal/iterations>{$cleanBy} limit {$insertByStep}")->fetchAll();
				if (empty($rotationRows)) {

					continue;
				}
			}
		}
	}

	foreach ($rotationRows as $row) {
		RedisManager::getInstance()->sadd(RedisManager::ROTATIONS, $row["id"]);
	}
	echo "add count: " . count($rotationRows) . "\n";
}