<?php

namespace Spells\Paladin\Holy;

/**
 * В нормальном бою все 25 стаков стикивают секунд за 3-5
 */
class JudgmentOfLight extends HPSpell {

	public function getDamageAmount() {
		// TODO: Implement getDamageAmount() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealDamageSPParams(): array {
		return [];
	}

	public function getRealHealSPParams(): array {
		return [
			1367 => 96,
			1341 => 94,
			1330 => 93,
			1280 => 89,
			1243 => 87,
			1182 => 82,
		];
	}
}