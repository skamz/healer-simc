<?php


namespace Pets\Spells;


use Spells\Priest\DC\DcSpell;

class MindbenderAttack extends DcSpell  {

	protected float $cd = 1.33;
	protected bool $hasteIsReduceCd = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.32589;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}