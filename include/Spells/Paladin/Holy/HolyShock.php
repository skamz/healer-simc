<?php

namespace Spells\Paladin\Holy;

class HolyShock extends HPSpell {

	protected int $gainHolyPower = 1;
	protected bool $isTriggerBeaconHeal = true;

	public function getDamageAmount() {
	}

	public function getRealDamageSPParams(): array {
		// TODO: Implement getRealDamageSPParams() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealHealSPParams(): array {
		return [
			1367 => 2118,
			1341 => 2078,
			1330 => 2061,
			1280 => 1984,
			1243 => 1926,
			1182 => 1832,
		];
	}
}