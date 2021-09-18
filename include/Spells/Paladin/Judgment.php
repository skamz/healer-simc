<?php

namespace Spells\Paladin;

class Judgment extends \Spell {

	public function getDamageAmount() {
		// TODO: Implement getDamageAmount() method.
	}

	public function getHealAmount(): int {
		// TODO: Implement getHealAmount() method.
	}

	public function getRealDamageSPParams(): array {
		return [
			1367 => 1910,
			1280=>1788,
		];
	}

	public function getRealHealSPParams(): array {
		return [];
	}
}