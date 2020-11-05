<?php


class CalculatorFactory {

	public static function createMasteryCalculator(string $spec): MasteryCalculator {
		$defaultValue = \Enum\DefaultStatPercents::getSpecStat($spec, \Enum\DefaultStatPercents::MASTERY);
		$masteryInfo = require(__DIR__ . "/stat_sourse/specs/{$spec}/mastery_penalty.php");
		return new MasteryCalculator($masteryInfo, $defaultValue);
	}

	public static function createCritCalculator(string $spec): CritCalculator {
		$defaultValue = \Enum\DefaultStatPercents::getSpecStat($spec, \Enum\DefaultStatPercents::CRIT);
		$critInfo = new BaseCritInfo();
		return new CritCalculator($critInfo, $defaultValue);
	}

	public static function createHasteCalculator(string $spec): HasteCalculator {
		$defaultValue = \Enum\DefaultStatPercents::getSpecStat($spec, \Enum\DefaultStatPercents::HASTE);
		$hasteInfo = new BaseHasteInfo();
		return new HasteCalculator($hasteInfo, $defaultValue);
	}

	public static function createVersatilityCalculator(string $spec): VersatilityCalculator {
		$defaultValue = \Enum\DefaultStatPercents::getSpecStat($spec, \Enum\DefaultStatPercents::VERSA);
		$versaInfo = new BaseVersatilityInfo();
		return new VersatilityCalculator($versaInfo, $defaultValue);
	}

	public static function createStatCalculator(string $spec): StatCalculator {
		self::registerSpecSpells($spec);

		$crit = self::createCritCalculator($spec);
		$mastery = self::createMasteryCalculator($spec);
		$haste = self::createHasteCalculator($spec);
		$versa = self::createVersatilityCalculator($spec);

		return new StatCalculator($crit, $haste, $mastery, $versa);
	}

	public static function registerSpecSpells(string $spec) {
		switch ($spec) {
			case \Enum\SpecList::PRIEST_DC:
				\Spells\Priest\RegisterSpells::run();
				\Spells\Priest\DC\RegisterSpells::run();
				break;

			case \Enum\SpecList::PRIEST_HOLY:
				\Spells\Priest\RegisterSpells::run();
				break;

		}
	}

}