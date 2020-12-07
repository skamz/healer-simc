<?php


class Helper {

	public static function isProc($chance, $max = 100, $precision = 100) {
		$rnd = rand(0, $max * $precision) / $precision;
		if ($chance >= $rnd) {
			return true;
		}
		return false;
	}

	public static function getMedian(array $arr) {
		if (empty($arr)) {
			return false;
		}
		sort($arr);
		$num = count($arr);
		$middleVal = floor(($num - 1) / 2);
		if ($num % 2) {
			return $arr[$middleVal];
		} else {
			$lowMid = $arr[$middleVal];
			$highMid = $arr[$middleVal + 1];
			return (($lowMid + $highMid) / 2);
		}
	}

	public static function getCountPlayersWithBuff($buffName) {
		$return = 0;

		$players = Place::getInstance()->getAllPlayers();
		/** @var Player $player */
		foreach ($players as $player) {
			if ($player->hasBuff($buffName) !== null) {
				$return++;
			}
		}
		return $return;
	}

	public static function stopCalculation() {
		$sqls = [
			"truncate table priest_dc",
			"truncate table priest_dc_result",
			"truncate table priest_dc_work",
		];
		foreach ($sqls as $sql) {
			Database::getInstance()->query($sql);
		}

		$redisKeys = [
			RedisManager::AVG_RESULT,
			RedisManager::ROTATIONS,
			RedisManager::FUTURE_ROTATION,
			RedisManager::SIM_RESULTS,
			RedisManager::STAT_INT,
			RedisManager::STAT_CRIT,
			RedisManager::STAT_HASTE,
			RedisManager::STAT_MASTERY,
			RedisManager::STAT_VERSA,
		];
		foreach ($redisKeys as $redisKey) {
			RedisManager::getInstance()->del($redisKey);
		}
	}

	public static function resetStats() {
		$redis = RedisManager::getInstance();
		$map = [
			RedisManager::STAT_INT => "setInt",
			RedisManager::STAT_CRIT => "setCrit",
			RedisManager::STAT_HASTE => "setHaste",
			RedisManager::STAT_MASTERY => "setMatery",
			RedisManager::STAT_VERSA => "setVersatility",
		];
		foreach ($map as $redisKey => $callback) {
			$statValue = $redis->get($redisKey);
			if (!empty($statValue)) {
				echo "{$callback} = {$statValue}\n";
				Player::getInstance()->$callback($statValue);
			}
		}
	}

	public static function getDoneIterations() {
		$sql = "SELECT avg(`iterations`) as avgIt FROM priest_dc_result";
		$avgInfo = Database::getInstance()->query($sql)->fetchArray();
		return $avgInfo["avgIt"];
	}

	public static function fillStartRotations() {
		$command = "/usr/bin/php " . dirname(__FILE__) . "/Generators/priest_dc.php";
		exec($command);
	}

}