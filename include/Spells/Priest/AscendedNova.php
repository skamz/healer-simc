<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedNova extends DcSpell {

	protected int $targetCount = 6;
	protected string $spellSchool = Arcane::class;

	public static function isAvailable(): bool {
		$cdAvailable = parent::isAvailable();
		if (!$cdAvailable) {
			return false;
		}
		return \Place::getInstance()->getMyPlayer()->hasBuff(\Buffs\Priest\BoonOfAscended::class);
	}

	public function applyBuffs(): array {
		// увеличивает количество ставок бафа BoonOfAscended на 1 за каждый таргет
		return [];
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.74;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}
}
