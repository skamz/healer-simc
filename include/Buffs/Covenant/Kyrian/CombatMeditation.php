<?php

namespace Buffs\Covenant\Kyrian;

use Spell;

class CombatMeditation extends \Buff {

	const INC_MASTERY_AMOUNT = 315;

	protected float $duration = 57;

	public function applyMasteryAmount(int $amount, Spell $fromSpell = null): float {
		return $amount + self::INC_MASTERY_AMOUNT;
	}

}