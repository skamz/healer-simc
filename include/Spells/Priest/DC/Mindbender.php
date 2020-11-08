<?php


namespace Spells\Priest\DC;


class Mindbender extends DcSpell {

	// cd=1.65

	protected bool $isTriggeredAtonement = true;

	public function getTickDamage() {
		$return = \Player::getInstance()->getInt() * 0.32589;
		return \Spell::applySecondary($return);
	}


}