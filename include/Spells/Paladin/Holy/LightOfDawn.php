<?php

namespace Spells\Paladin\Holy;

class LightOfDawn extends HPSpell {

	protected int $targetCount = 5;

	public function getDamageAmount() {
		// TODO: Implement getDamageAmount() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealDamageSPParams(): array {
		// TODO: Implement getRealDamageSPParams() method.
	}

	public function getRealHealSPParams(): array {
		return [
			1367 => 1435,
			1341 => 1408,
			1330 => 1396,
			1280 => 1344,
			1243 => 1305,
			1182 => 1241,
		];
	}
}