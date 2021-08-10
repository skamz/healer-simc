<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Shadow;

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
	protected string $spellSchool = Shadow::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 2.537;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}