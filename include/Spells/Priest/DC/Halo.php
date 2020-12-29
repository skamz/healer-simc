<?php


namespace Spells\Priest\DC;


class Halo extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 40;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.9677858903;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}