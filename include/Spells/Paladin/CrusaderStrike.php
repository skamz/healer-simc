<?php

namespace Spells\Paladin;

use Spells\Paladin\Holy\HPSpell;

class CrusaderStrike extends HPSpell {

	protected int $gainHolyPower = 1;
	protected int $changeCount = 2;
	protected float $cd = 6;
	protected bool $hasteIsReduceCd = true;

	public function getDamageAmount() {
		// TODO: Implement getDamageAmount() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealDamageSPParams(): array {
		return [
			1367 => 1000,
			1280 => 937,
		];
	}

	public function getRealHealSPParams(): array {
		return [];
	}
}