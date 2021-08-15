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
	protected bool $isDamageSpell = true;

	public static function isAvailable(): bool {
		$cdAvailable = parent::isAvailable();
		if (!$cdAvailable) {
			return false;
		}
		$hasBuff = \Place::getInstance()->getMyPlayer()->hasBuff(\Buffs\Priest\BoonOfAscended::class);
		return (bool)$hasBuff;
	}

	public function applyBuffs(): array {
		// увеличивает количество ставок бафа BoonOfAscended на 5
		return [];
	}

	public function getHealAmount(): int {
		// хилит ближайший таргет на 120% инты
		return 0;
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1370 => 3162,
			1309 => 3066,
			1288 => 3051,
			1227 => 2991,
		];
	}

}