<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Fire;

class PurgeWickedDot extends DcSpell {

	protected float $cd = 0;
	protected float $gcd = 0;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Fire::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE) * $this->damageModifier;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1087 => 141,
			1061 => 138,
			1000 => 130,
			995 => 129,
			918 => 119,
		];
	}

}