<?php

namespace Spells\Paladin\Holy;

use Events\Event;

/**
 * В нормальном бою все 25 стаков стикивают секунд за 3-5
 */
class JudgmentOfLight extends HPSpell {

	const TARGET_COUNT = 25;
	protected float $cd = 0;
	protected float $gcd = 0;
	protected int $changeCount = 1000;

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