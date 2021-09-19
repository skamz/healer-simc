<?php

namespace Spells\Paladin\Holy;

class HolyShock extends HPSpell {

	protected float $cd = 7.5;
	protected bool $hasteIsReduceCd = true;
	protected int $gainHolyPower = 1;
	protected bool $isTriggerBeaconHeal = true;

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(static::SP_TYPE_HEAL);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
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