<?php


namespace Pets\Spells;


use Spells\Priest\DC\DcSpell;

class MindbenderAttack extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 1.49;
	protected bool $hasteIsReduceCd = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.3262805438;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}