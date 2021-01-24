<?php


namespace Buffs\Covenant;


use Spells\Priest\DC\Mindgames;

class Shattered extends \Buff {

	const INC_PERCENT = 1.182;

	public function increaseDamage(int $damageCount, \Spell $fromSpell = null): int {
		if (isset($fromSpell) && $fromSpell->getName() == Mindgames::class) {
			return round($damageCount * self::INC_PERCENT);
		}
		return $damageCount;
	}

}