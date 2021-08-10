<?php

namespace Buffs\Priest;

use Spells\Priest\AscendedBlast;
use Spells\Priest\AscendedEruption;
use Spells\Priest\AscendedNova;

class BoonOfAscended extends \Buff {

	//  Урон и исцеление от "Волны перерождения" усилены на 3% за стак
	const INCREASE_BY_STACK = 0.03;

	protected float $duration = 20;

	public function increaseAscendedSpell(int $amount, \Spell $fromSpell = null): int {
		$increase = [
			AscendedBlast::class,
			AscendedNova::class,
			AscendedEruption::class,
		];
		if (empty($fromSpell) || !in_array($fromSpell->getName(), $increase)) {
			return $amount;
		}
		$increasePercent = $this->stackCount * self::INCREASE_BY_STACK;
		return round($amount * (1 + $increasePercent));
	}

	public function increaseDamage(int $damageCount, \Spell $fromSpell = null): int {
		return $this->increaseAscendedSpell($damageCount, $fromSpell);
	}

	public function increaseHeal(int $damageCount, \Spell $fromSpell = null): int {
		return $this->increaseAscendedSpell($damageCount, $fromSpell);
	}

	public function applyFade() {
		// взрыв в зависимости от количества стаков
		$spellObject = new AscendedEruption();
		$toEnemy = \Place::getInstance()->getRandomEnemy();
		\Caster::applySpellToEnemy($toEnemy, $spellObject);

		$toPlayer = \Place::getInstance()->getRandomNumPlayer();
		\Caster::applySpellToPlayer($toPlayer, $spellObject);
	}

	public function applyOnDamage(int $damageCount, \Spell $fromSpell = null): int {
		return round($damageCount * 1.25);
	}

}