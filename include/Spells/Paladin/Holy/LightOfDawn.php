<?php

namespace Spells\Paladin\Holy;

class LightOfDawn extends HPSpell {

	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;
	protected int $targetCount = 5;
	protected static int $costHolyPower = 3;

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

	public function applySpecial() {
		parent::applySpecial();

	}
}