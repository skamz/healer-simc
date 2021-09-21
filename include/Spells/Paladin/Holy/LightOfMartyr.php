<?php

namespace Spells\Paladin\Holy;

use Buffs\Paladin\Holy\UntemperedDedication;

class LightOfMartyr extends HPSpell {

	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;
	protected bool $isTriggerBeaconHeal = true;

	public function applySpecial() {
		parent::applySpecial();
		$this->applyUntemperedDedication();
	}

	public function afterHeal(int $healCount) {
		parent::afterHeal($healCount);
		$this->applyBeaconHeal($healCount);
	}

	protected function applyUntemperedDedication() {
		$buff = \Player::getInstance()->getBuff(UntemperedDedication::class);
		$buffBase = new UntemperedDedication();
		if (empty($buff)) {
			\Player::getInstance()->addBuff($buffBase);
		} else {
			$buff->increaseStackCount(1);
			$buff->reApply($buffBase);
		}
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