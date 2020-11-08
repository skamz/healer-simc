<?php


namespace Spells\Priest\DC;


/**
 * Способность ковенантов Вентиров
 * @package Spells\Priest\DC
 */
class Mindgames extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $castTime = 1.5;
	protected bool $hasteIsReduceCastTime = true;
	protected float $manaPercentCost = 2;
	protected float $cd = 45;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 3;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}