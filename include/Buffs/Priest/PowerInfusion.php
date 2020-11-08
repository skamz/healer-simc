<?php


namespace Buffs\Priest;


class PowerInfusion extends \Buff {

	protected float $duration = 20;

	public function applyHastePercents(float $percents, \Spell $fromSpell = null): float {
		$incPercent = 25;
		return $percents * (1 + $incPercent / 100) + 25;
	}
}