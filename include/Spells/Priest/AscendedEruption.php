<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedEruption extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Arcane::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 2.1;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getHealAmount() {
		$return = \Player::getInstance()->getInt() * 1.59;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}