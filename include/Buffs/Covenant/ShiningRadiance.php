<?php


namespace Buffs\Covenant;


use Spells\Priest\DC\PowerWordRadiance;

class ShiningRadiance extends \Buff {

	const INC_PERCENT = 1.4;

	public function increaseHeal(int $healCount, \Spell $fromSpell = null): int {
		if ($fromSpell->getName() == PowerWordRadiance::class) {
			return round($healCount * self::INC_PERCENT);
		}
		return $healCount;
	}
}