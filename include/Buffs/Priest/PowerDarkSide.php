<?php


namespace Buffs\Priest;


use Spells\Priest\DC\Penance;

class PowerDarkSide extends \Buff {

	protected float $duration = 20;
	const INC_PERCENT = 1.5;

	public function increaseDamage(int $damageCount, \Spell $fromSpell = null): int {
		if ($fromSpell->getName() == Penance::class) {
			return round($damageCount * self::INC_PERCENT);
		}
		return $damageCount;
	}

}