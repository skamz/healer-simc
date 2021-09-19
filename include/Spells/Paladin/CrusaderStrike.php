<?php

namespace Spells\Paladin;

use Spells\Paladin\Holy\HPSpell;

class CrusaderStrike extends HPSpell {

	protected int $gainHolyPower = 1;
	protected int $changeCount = 2;
	protected int $maxChangeCount = 2;
	protected float $cd = 6;
	protected bool $hasteIsReduceCd = true;
	protected bool $isDamageSpell = true;

	public function getRealDamageSPParams(): array {
		return [
			1367 => 1000,
			1280 => 937,
		];
	}

}