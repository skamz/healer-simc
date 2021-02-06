<?php


namespace Pets\Spells;


use Buffs\Covenant\RabidShadows;
use Spells\Priest\DC\DcSpell;

class MindbenderAttack extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;

	public function __construct() {
		parent::__construct();
		if (\Player::getInstance()->hasBuff(\Buffs\Covenant\RabidShadows::class) !== null) {
			$this->cd /= 1 + RabidShadows::PERCENT / 100;
		}
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.3259;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}