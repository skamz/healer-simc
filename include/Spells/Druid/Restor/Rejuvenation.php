<?php

namespace Spells\Druid\Restor;


class Rejuvenation extends \Spell {

	protected float $manaPercentCost = 2.2;
	protected float $gcd = 1.5;

	public function getHealAmount(): int {
		return 0;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Druid\Restor\Rejuvenation(),
		];
	}

}