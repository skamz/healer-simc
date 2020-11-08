<?php


namespace Buffs\Covenant;


use Spells\Priest\DC\Penance;

class SwiftPenitence extends \Buff {

	const INC_PERCENT = 1.3;

	protected static float $lastApply = -10;
	const INNER_CD = 3;

	protected static function setLastApply() {
		self::$lastApply = \TimeTicker::getInstance()->getCombatTimer();
	}

	protected static function isCanApply() {
		$lost = \TimeTicker::getInstance()->getCombatTimer() - self::$lastApply;
		if ($lost > self::INNER_CD) {
			return true;
		}
		return false;
	}

	public function increaseDamage(int $damageCount, \Spell $fromSpell = null): int {
		if ($fromSpell->getName() == Penance::class && self::isCanApply()) {
			self::setLastApply();
			return round($damageCount * self::INC_PERCENT);
		}
		return $damageCount;
	}
}