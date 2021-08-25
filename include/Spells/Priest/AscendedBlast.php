<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedBlast extends DcSpell {

	const BOON_OF_ASCENDED_STACKS = 5;

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
		\Player::getInstance()->getBuff(\Buffs\Priest\BoonOfAscended::class)->increaseStackCount(self::BOON_OF_ASCENDED_STACKS);
		return [];
	}

	public function getHealAmount(): int {
		// хилит ближайший таргет на 120% инты
		return 0;
	}

	public function getDamageAmount(): int {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	// это костыль, есть баф CourageousAscension который сейчас не применяется
	public function getRealDamageSPParams(): array {
		return [
			1370 => 3162,
			1309 => 3066,
			1288 => 3051,
			1227 => 2991,
		];
	}

}