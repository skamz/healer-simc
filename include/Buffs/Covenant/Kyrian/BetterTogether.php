<?php

namespace Buffs\Covenant\Kyrian;

class BetterTogether extends \Buff {

	const INC_MASTERY_AMOUNT = 40;
	protected float $duration = 999;

	public function applyMasteryAmount(int $amount, \Spell $fromSpell = null): float {
		return $amount + self::INC_MASTERY_AMOUNT;
	}

}