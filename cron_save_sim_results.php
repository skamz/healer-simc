<?php
exit;
require_once(__DIR__ . "/autoloader.php");

function savePoll(Database $db, array $poll) {
	if (empty($poll)) {
		return;
	}
	//$db->query("update priest_dc set total_heal = total_heal + {$totalResult}, iterations = iterations + 1, avg_heal = total_heal / iterations where id ={$rotationInfo["id"]}");
	$sqlValuesList = [];
	$removeId = [];

	foreach ($poll as $row) {
		$rotation = trim($row["rotation"]);
		if (empty($rotation)) {
			continue;
		}
		$sqlValuesList[] = "({$row["id"]}, '{$rotation}',{$row["heal"]},{$row["heal"]}, 1, " . intval($row["time"]) . ")";

		$sql = "delete from priest_dc_work where id = " . $row["id"] . " limit 1";
		echo $sql . "\n";
		$db->query($sql);

		$sql = "delete from priest_dc where id = " . $row["id"] . " limit 1";
		$db->query($sql);
	}
	if (!empty($sqlValuesList)) {
		$sql = "insert into priest_dc_result(id, rotation, total_heal, avg_heal, iterations, total_time) values" . implode(", ", $sqlValuesList) . " 
		ON DUPLICATE KEY UPDATE 
			total_heal=total_heal + VALUES(total_heal), 
			iterations = iterations + 1, 
			avg_heal = total_heal / iterations,
			total_time = total_time + VALUES(total_time)";
		$db->query($sql)->affectedRows();
	}
}

$db = new Database();
$poll = [];
$sleepCounter = 0;
while (true) {
	while (RedisManager::getInstance()->scard(RedisManager::SIM_RESULTS) > 0) {
		$saveRow = RedisManager::getInstance()->spop(RedisManager::SIM_RESULTS);
		$poll[] = json_decode($saveRow, true);
		if (count($poll) >= 10) {
			savePoll($db, $poll);
			$poll = [];
			$sleepCounter = 0;
		}
	}
	sleep(1);
	$sleepCounter++;
	if ($sleepCounter >= 50) {
		break;
	}
}
savePoll($db, $poll);