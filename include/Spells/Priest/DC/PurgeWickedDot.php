<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Fire;

class PurgeWickedDot extends DcSpell {

	protected float $cd = 0;
	protected float $gcd = 0;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Fire::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.115331 * $this->damageModifier;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}