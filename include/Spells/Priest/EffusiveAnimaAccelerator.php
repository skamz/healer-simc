<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;

class EffusiveAnimaAccelerator extends DcSpell {

	protected float $gcd = 0;

	public function getDamageAmount(): int {
		return 0;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Covenant\Kyrian\EffusiveAnimaAccelerator()
		];
	}

}