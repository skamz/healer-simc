<?php

namespace Mediums\Kyrian;

use Mediums\Medium;
use Spells\Priest\BoonOfAscended;
use Spells\Priest\EffusiveAnimaAccelerator;

class Mikanikos extends Medium {

	public function getTriggerMap(): array {
		return [
			BoonOfAscended::class => [
				"boonOfAscendedAfterCast",
			],
		];
	}

	public function getApplyBuffs(): array {
		return [];
	}

	public function boonOfAscendedAfterCast() {
		$dot = new EffusiveAnimaAccelerator();
		\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), $dot);
	}

}