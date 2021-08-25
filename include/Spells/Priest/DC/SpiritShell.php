<?php

namespace Spells\Priest\DC;

use Buffs\Priest\Atonement;
use Legendary\Priest\ClarityOfMind;

class SpiritShell extends DcSpell {

	const PROLONG_ATONEMENT = 3;
	protected float $gcd = 0;
	protected float $cd = 90;

	public function applySpecial() {
		if (ClarityOfMind::isLegOnPlayer()) {
			\Events::getInstance()->prolongBuffByName(Atonement::class, intval(self::PROLONG_ATONEMENT / \TimeTicker::TICK_COUNT));
		}
	}

	public function getHealAmount(): int {
		return 0;
	}

}