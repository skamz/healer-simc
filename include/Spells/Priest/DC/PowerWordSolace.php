<?php


namespace Spells\Priest\DC;


class PowerWordSolace extends DcSpell {

	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 15;
	protected bool $hasteIsReduceCd = true;
	protected float $travelTime = 0.5;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.8;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}


}