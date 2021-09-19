<?php

namespace Spells\Paladin\Holy;

class LightOfMartyr extends HPSpell {

	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;
	protected bool $isTriggerBeaconHeal = true;

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