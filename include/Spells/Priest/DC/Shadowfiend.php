<?php


namespace Spells\Priest\DC;


class Shadowfiend extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;

	public function getTickDamage() {
		$return = \Player::getInstance()->getInt() * 0.44642;
		return \Spell::applySecondary($return);
	}


}