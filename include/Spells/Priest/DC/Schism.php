<?php


namespace Spells\Priest\DC;


class Schism extends DcSpell {

	protected float $manaPercentCost = 0.5;
	protected float $cd = 24;
	protected float $gcd = 1.5;
	protected float $castTime = 1.5;
	protected bool $isTriggeredAtonement = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 1.41;
		return $this->applySecondary($return);
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\Schism(),
		];
	}

}