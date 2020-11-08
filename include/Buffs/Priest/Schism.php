<?php


namespace Buffs\Priest;


class Schism extends \Debuff {

	protected float $duration = 9;

	public function applyOnDamage(int $damageCount, \Spell $fromSpell = null): int {
		return round($damageCount * 1.25);
	}

}