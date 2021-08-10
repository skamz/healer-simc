<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Shadow;

class ShadowWordPainDot extends DcSpell {

	protected float $cd = 0;
	protected float $gcd = 0;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Shadow::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.09591816562 * $this->damageModifier;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}