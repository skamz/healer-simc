<?php

class WorkRotationManager {

	public static function storeResult($id, $healAmount, $iterations) {
		Database::getInstance()->query("update priest_dc_work set total_heal=total_heal+{$healAmount}, iterations = iterations + {$iterations} where id = {$id}");
	}

	public static function addSpells(array $spellList, string $rotation) {
		return false;
		$return = [];
		foreach ($spellList as $spellClass) {
			if ($spellClass::isAvailable()) {
				Details::log("$spellClass isAvailable");
				$spellNum = \Rotations\Priest\DCSpells::getSpellNum($spellClass);
				$return[] = trim("{$rotation} {$spellNum}");
			}
		}
		self::registerRotations($return);
	}

	public static function registerRotations(array $rotations) {
		foreach ($rotations as $testRotation) {
			RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, $testRotation);
		}
	}

	public static function getHealAmount($data) {
		return $data["atonement"];
	}

	public static function runCommand($execCommand): array {
		$out = [];

		exec($execCommand, $out);
		$out = array_filter($out);
		if (count($out) > 1) {
			throw new Exception("Stat script in debug mode");
		}
		$jsonData = array_shift($out);
		return json_decode($jsonData, true);
	}

	public static function executeHealRotation($execCommand) {
		$jsonData = self::runCommand($execCommand);
		return self::getHealAmount($jsonData);
	}

	public static function fetchRotation(): array {
		$db = Database::getInstance();
		$id = RedisManager::getInstance()->spop(RedisManager::ROTATIONS);
		if (empty($id)) {
			throw new Exception("No test rotations");
		}

		$rotationInfo = $db->query("select * from priest_dc_work where id = {$id} limit 1 ")->fetchArray();
		if (empty($rotationInfo)) {
			throw new Exception("rotation not found");
		}

		return $rotationInfo;
	}

	public static function getRunCount() {
		$return = RedisManager::getInstance()->get(RedisManager::RUN_ROTATION_COUNT);
		return $return ?: 10;
	}

}