<?php


namespace Enum;


class DefaultStatPercents {

	const CRIT = "crit";
	const HASTE = "haste";
	const MASTERY = "mastery";
	const VERSA = "versa";

	public static function getDefaultStats() {
		return [
			SpecList::PRIEST_DC => [
				self::CRIT => 5,
				self::HASTE => 0,
				self::MASTERY => 11,
				self::VERSA => 0
			],
		];
	}

	public static function getSpecStats(string $spec): array {
		$specsStats = self::getDefaultStats();
		if (empty($specsStats[$spec])) {
			throw new \Exception("DefaultStatPercents для спека {$spec} не указаны");
		}
		return $specsStats[$spec];
	}

	public static function getSpecStat(string $spec, string $statName) {
		$specStats = self::getSpecStats($spec);
		return $specStats[$statName];
	}

}