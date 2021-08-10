<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedBlast extends DcSpell {

	protected float $gcd = 1;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 3;
	protected bool $hasteIsReduceCd = true;
	protected string $spellSchool = Arcane::class;

	public static function isAvailable(): bool {
		$cdAvailable = parent::isAvailable();
		if (!$cdAvailable) {
			return false;
		}
		return \Place::getInstance()->getMyPlayer()->hasBuff(\Buffs\Priest\BoonOfAscended::class);
	}

	public function applyBuffs(): array {
		// увеличивает количество ставок бафа BoonOfAscended на 5
		return [];
	}

	public function getHealAmount() {
		// хилит ближайший таргет на 120% инты
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 1.79;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}