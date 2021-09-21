<?php

namespace Spells\Paladin\Holy;

class BeaconOfLightHeal extends HPSpell {

	protected float $cd = 0;
	protected float $gcd = 0;
	protected int $changeCount = 999999;
	protected int $healAmount;

	public function setHealAmount(int $amount) {
		$this->healAmount = $amount;
	}

	public function getHealAmount(): int {
		return $this->healAmount;
	}

}