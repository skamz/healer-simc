<?php

namespace Spells\Paladin\Holy;

class LightOfMartyr extends HPSpell {

	protected bool $isTriggerBeaconHeal = true;

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
			1367 => 2870,
			1341 => 2816,
			1330 => 2793,
			1280 => 2688,
			1243 => 2610,
			1182 => 2482,
		];
	}
}