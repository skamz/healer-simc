<?php


namespace Buffs\Priest;


class Schism extends \Buff {

	protected float $duration = 9;

	public function applyOnDamage(int $damageCount): int {
		return round($damageCount * 1.25);
	}

}