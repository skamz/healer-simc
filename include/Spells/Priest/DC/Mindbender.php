<?php


namespace Spells\Priest\DC;


class Mindbender extends DcSpell {

	protected bool $isTriggeredAtonement = true;

	public function getTickDamage() {
		$return = \Player::getInstance()->getInt() * 0.44642;
		return \Spell::applySecondary($return);
	}


}