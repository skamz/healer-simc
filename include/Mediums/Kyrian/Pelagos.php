<?php

namespace Mediums\Kyrian;

use Buffs\Covenant\Kyrian\BetterTogether;
use Buffs\Covenant\Kyrian\CombatMeditation;
use Mediums\Medium;
use Spells\Priest\BoonOfAscended;

class Pelagos extends Medium {

	public function getTriggerMap(): array {
		return [
			BoonOfAscended::class => [
				"boonOfAscendedAfterCast",
			],
		];
	}

	public function getApplyBuffs(): array {
		return [
			BetterTogether::class,
		];
	}

	public function boonOfAscendedAfterCast() {
		\Place::getInstance()->getMyPlayer()->addBuff(new CombatMeditation());
	}

}