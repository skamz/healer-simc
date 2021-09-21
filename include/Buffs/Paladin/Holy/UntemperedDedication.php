<?php

namespace Buffs\Paladin\Holy;

use Spell;
use Spells\Paladin\Holy\LightOfMartyr;

/**
 * Неумеренная преданность
 */
class UntemperedDedication extends \Buff {

	protected float $duration = 15;
	protected int $maxStackCount = 5;

	const INC_PER_STACK = 8.5; //@todo какой процент???

	public function increaseHeal(int $healCount, Spell $fromSpell = null): int {
		if (isset($fromSpell) && $fromSpell->getName() == LightOfMartyr::class) {
			$perPercent = $this->stackCount * self::INC_PER_STACK;
			$healCount = round($healCount * (1 + $perPercent / 100));
		}
		return $healCount;
	}

}