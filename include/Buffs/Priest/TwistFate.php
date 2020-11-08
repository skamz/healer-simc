<?php


namespace Buffs\Priest;


class TwistFate extends \Buff {

	protected float $duration = 10;

	public function increaseDamage(int $damageCount, \Spell $fromSpell = null): int {
		return round($damageCount * 1.20);
	}

}