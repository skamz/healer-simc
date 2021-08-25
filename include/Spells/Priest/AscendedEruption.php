<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedEruption extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Arcane::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 1.59;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1827 => 2806,
			1828 => 2821,
			1339 => 2085,
		];
	}

}