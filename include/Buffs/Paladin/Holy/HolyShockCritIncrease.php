<?php

namespace Buffs\Paladin\Holy;

use Spell;
use Spells\Paladin\Holy\HolyShock;

class HolyShockCritIncrease extends \Buff {

	public function applyCritPercents(float $percents, Spell $fromSpell = null): float {
		if (get_class($fromSpell) == HolyShock::class) {
			$percents += 30;
		}
		return $percents;
	}

}