<?php

namespace Spells\Paladin;

use Spells\Paladin\Holy\HPSpell;

class HammerOfWrath extends HPSpell {

	protected int $gainHolyPower = 1;

	public function getDamageAmount() {
		// TODO: Implement getDamageAmount() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealDamageSPParams(): array {
		return [
			1367 => 1413,
			1280=>1322,
		];
	}

	public function getRealHealSPParams(): array {
		return [];
	}
}