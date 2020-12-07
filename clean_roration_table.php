<?php

require_once(__DIR__ . "/autoloader.php");

function doWork() {
	$db = new Database();
	do {
		$avgInfo = $db->query("select avg(avg_heal) as aheal, count(*) as cnt from priest_dc_result where iterations > 0")->fetchArray();

		print_r($avgInfo);
		if ($avgInfo["cnt"] >= 2000) {
			$sql = "select id from priest_dc_result where iterations > 0 and avg_heal < " . intval($avgInfo["aheal"]) . " limit 5000";
			echo $sql . "\n";
			$idsRows = $db->query($sql)->fetchAll();
			$ids = array_column($idsRows, "id");

			$parts = array_chunk($ids, 10);
			foreach ($parts as $part) {
				$sql = "delete from priest_dc_result where id in (" . implode(",", $part) . ")";
				$db->query($sql);
			}
			RedisManager::getInstance()->del(RedisManager::AVG_RESULT);

		} else {
			break;
		}
	} while (true);

	$recalcCount = Settings::RECALC_ITERATION_COUNT;


	$sql = "select count(*) as cnt from priest_dc_work";
	$cntInfo = Database::getInstance()->query($sql)->fetchArray();
	if ($cntInfo["cnt"] > 10) {
		return false;
	}

	$doneIterations = Helper::getDoneIterations();
	if ($doneIterations <= $recalcCount) {
		$info = $db->query("select count(*) as cnt from priest_dc_work")->fetchArray();
		print_r($info);
		if ($info["cnt"] < 1000) {
			echo "less 1000 Add recheck\n";
			$db->query("insert ignore into priest_dc_work select * from priest_dc_result where iterations < {$recalcCount}");
		}
		return true;
	}
	return false;
}


$emptyCount = 0;
while (true) {
	$isSuccess = doWork();
	if ($isSuccess) {
		$emptyCount = 0;
	} elseif ($emptyCount++ > 100) {
		break;
	} else {
		sleep(1);
	}
}

