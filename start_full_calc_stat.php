<?php


require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

const INC_AMOUNT = 150;

function renameResultTable($resultType) {
	$sql = "rename table priest_dc_result to priest_dc_result_{$resultType}";
	Database::getInstance()->query($sql);
}

function createResultTable($postfix = "") {
	$sql = "create table priest_dc_result{$postfix} like priest_dc";
	Database::getInstance()->query($sql);

	Helper::stopCalculation();
	sleep(5);
	Helper::stopCalculation();
}

function waitEmptyWork() {
	echo "\nWait empty work table";
	do {
		sleep(30);
		$sql = "select count(*) as cnt from priest_dc_work";
		$row = Database::getInstance()->query($sql)->fetchArray();
	} while ($row["cnt"] > 0);

}

function waitCalculation() {
	do {
		sleep(10);
		$doneIterations = Helper::getDoneIterations();
		echo "Current done iterations: {$doneIterations} (" . round($doneIterations / Settings::RECALC_ITERATION_COUNT * 100) . "%)      \r";
	} while ($doneIterations < Settings::RECALC_ITERATION_COUNT);
	waitEmptyWork();
}

function getBaseRotationsStat($postfix) {
	$sql = "SELECT * FROM `priest_dc_result{$postfix}` where avg_heal>
	(select max(avg_heal) from priest_dc_result{$postfix})*0.95 
	order by total_heal/total_time desc
	limit 5";
	$rows = Database::getInstance()->query($sql)->fetchAll();
	$return = [
		"sources" => [],
		"output" => [],
		"rotations" => [],
	];
	foreach ($rows as $row) {
		$return["source"][] = $row;
		$return["heal"] += $row["avg_heal"];

		$timer = round($row["total_time"] / $row["iterations"], 1);
		$return["output"][] = "{$row["rotation"]} ({$timer} sec). Heal {$row["avg_heal"]}\n";
		$return["rotations"][] = $row["rotation"];
	}
	$return["heal"] = round($return["heal"] / count($return["output"]));
	return $return;
}

function getBaseRotations() {
	$statInfo = [];
	$statInfo["base"] = getBaseRotationsStat("_base");
	$statInfo["int"] = getBaseRotationsStat("_int");
	$statInfo["crit"] = getBaseRotationsStat("_crit");
	$statInfo["haste"] = getBaseRotationsStat("_haste");
	$statInfo["mastery"] = getBaseRotationsStat("_mastery");
	$statInfo["varsa"] = getBaseRotationsStat("_versa");

	$incResults = [
		"int" => $statInfo["int"]["heal"] - $statInfo["base"]["heal"],
		"crit" => $statInfo["crit"]["heal"] - $statInfo["base"]["heal"],
		"haste" => $statInfo["haste"]["heal"] - $statInfo["base"]["heal"],
		"mastery" => $statInfo["mastery"]["heal"] - $statInfo["base"]["heal"],
		"varsa" => $statInfo["varsa"]["heal"] - $statInfo["base"]["heal"],
	];
	foreach ($incResults as $stat => $incHps) {
		$incResults[$stat] = round($incHps / INC_AMOUNT, 1);
	}
	print_r($incResults);
	$max = max($incResults);
	foreach ($incResults as $stat => $incHps) {
		$incResults[$stat] = round($incHps / $max, 2);
	}

	uasort($incResults, function($a, $b) {
		return $b * 100 - $a * 100;
	});
	print_r($incResults);

	printBestRotations($statInfo);
}

function printBestRotations(array $statInfo) {
	$rotations = [];
	foreach ($statInfo as $stat => $statInfo) {
		foreach ($statInfo["rotations"] as $rotation) {
			if (empty($rotations[$rotation])) {
				$rotations[$rotation] = [];
			}
			$rotations[$rotation][] = $stat;
		}
	}

	uasort($rotations, function($a, $b) {
		return count($b) - count($a);
	});
	foreach ($rotations as $rotation => $stats) {
		sort($stats);
		echo "$rotation - best for " . count($stats) . " (" . implode(" ", $stats) . ")\n";
	}
}

function dropTables() {
	// drop tables
	$dropTables = [
		"priest_dc_result",
		"priest_dc_result_base",
		"priest_dc_result_int",
		"priest_dc_result_crit",
		"priest_dc_result_haste",
		"priest_dc_result_mastery",
		"priest_dc_result_versa",
	];
	foreach ($dropTables as $dropTable) {
		try {
			$sql = "drop table {$dropTable}";
			Database::getInstance()->query($sql);
		} catch (Exception $e) {
		}
	}
}

echo "Drop old tables\n";
dropTables();


echo "\n\n =================== Start calc BASE ===================\n";
createResultTable();
Helper::fillStartRotations();
waitCalculation();
renameResultTable("base");

echo "\n\n =================== Start calc INT ===================\n";
createResultTable();
RedisManager::getInstance()->set(RedisManager::STAT_INT, Player::getInstance()->getInt() + INC_AMOUNT); // set Redis stat

Helper::fillStartRotations();

waitCalculation();
renameResultTable("int");


echo "\n\n =================== Start calc CRIT ===================\n";
createResultTable();
RedisManager::getInstance()->set(RedisManager::STAT_CRIT, Player::getInstance()->getCritCount() + INC_AMOUNT); // set Redis stat
Helper::fillStartRotations();
waitCalculation();
renameResultTable("crit");

echo "\n\n =================== Start calc HASTE ===================\n";
createResultTable();
RedisManager::getInstance()->set(RedisManager::STAT_HASTE, Player::getInstance()->getHasteCount() + INC_AMOUNT); // set Redis stat
Helper::fillStartRotations();
waitCalculation();
renameResultTable("haste");


echo "\n\n =================== Start calc MASTERY ===================\n";
createResultTable();
RedisManager::getInstance()->set(RedisManager::STAT_MASTERY, Player::getInstance()->getMasteryCount() + INC_AMOUNT); // set Redis stat
Helper::fillStartRotations();
waitCalculation();
renameResultTable("mastery");


echo "\n\n =================== Start calc VERSA ===================\n";
createResultTable();
RedisManager::getInstance()->set(RedisManager::STAT_VERSA, Player::getInstance()->getVersatilityCount() + INC_AMOUNT); // set Redis stat
Helper::fillStartRotations();
waitCalculation();
renameResultTable("versa");


getBaseRotations();