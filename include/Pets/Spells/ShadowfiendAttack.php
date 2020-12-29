<?php


namespace Pets\Spells;


use Spells\Priest\DC\DcSpell;

class ShadowfiendAttack extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.44642;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}