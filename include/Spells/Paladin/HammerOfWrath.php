<?php

namespace Spells\Paladin;

use Buffs\Paladin\AvengingWrath;
use Spells\Paladin\Holy\HPSpell;

class HammerOfWrath extends HPSpell {

	protected float $cd = 7.5;
	protected bool $hasteIsReduceCd = true;
	protected int $gainHolyPower = 1;
	protected bool $isDamageSpell = true;

	public static function isAvailable(): bool {
		if (!parent::isAvailable()) {
			return false;
		}
		if (!\Player::getInstance()->existBuff(AvengingWrath::class)) {
			return false;
		}
		return true;
	}

	public function getRealDamageSPParams(): array {
		return [
			1367 => 1413,
			1280 => 1322,
		];
	}

}