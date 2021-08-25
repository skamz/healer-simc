<?php

namespace Buffs\Covenant\Kyrian;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class EffusiveAnimaAcceleratorDot extends DcSpell {

	protected float $cd = 0;
	protected float $gcd = 0;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Arcane::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE) * $this->damageModifier;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1339 => 1808,
			1338 => 1808,
		];
	}
}