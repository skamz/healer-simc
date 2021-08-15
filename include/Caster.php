<?php


class Caster {

	public static function playerCastSpell(int $toUnit, Spell $spell) {
		\Buffs\RealPPM::getInstance()->tryProc(3, new \Buffs\CelestialGuidance());
		\Buffs\RealPPM::getInstance()->tryProc(1.5, new \Buffs\GladiatorInsignia());
		if ($spell->isDamageSpell()) {
			self::castSpellToEnemy($toUnit, $spell);
		} else {
			self::castSpellToPlayer($toUnit, $spell);
		}
	}

	public static function castSpellToPlayer(int $toPlayer, Spell $spell) {
		if ($spell->getCastTime() > 0) {
			SpellState::getInstance()->startCastingSpell(SpellState::CAST_TO_PLAYER, $toPlayer, $spell);
		} else {
			self::applySpellToPlayer($toPlayer, $spell);
		}
		TimeTicker::getInstance()->startGcd($spell->getGcd());
	}

	protected static function cast(Spell $spell) {
		$spell->applySpecial();
		SpellState::getInstance()->cast($spell->getName());
		$spell->afterSuccessCast();
	}

	public static function castSpellToEnemy(int $enemyId, Spell $spell) {
		if ($spell->getCastTime() > 0) {
			SpellState::getInstance()->startCastingSpell(SpellState::CAST_TO_ENEMY, $enemyId, $spell);
		} else {
			self::applySpellToEnemy($enemyId, $spell);
		}
		TimeTicker::getInstance()->startGcd($spell->getGcd());
	}

	public static function applySpellToPlayer(int $toPlayer, Spell $spell) {
		if (Details::$isLog) {
			echo "===========" . TimeTicker::getInstance()->getCombatTimer() . " Cast " . get_class($spell) . " to {$toPlayer}<br>\n";
		}
		$applyToPlayers = [];
		$applyToNumbers = $spell->getSpellCommonTargets($toPlayer);

		foreach ($applyToNumbers as $playerNumber) {
			$applyPlayer = Place::getInstance()->getPlayer($playerNumber);
			$applyToPlayers[] = $applyPlayer;
		}

		foreach ($applyToPlayers as $player) {
			/** @var $player Player */
			$player->healTaken($spell->getHealAmount(), $spell->getName());

			foreach ($spell->applyBuffs() as $applyBuff) {
				$player = $player->addBuff(clone $applyBuff);
			}
		}
		self::cast($spell);
	}


	public static function applySpellToEnemy(int $enemyId, Spell $spell) {
		if (Details::$isLog) {
			echo "===========" . TimeTicker::getInstance()->getCombatTimer() . " Cast " . get_class($spell) . "<br>\n";
		}
		$enemyObj = Place::getInstance()->getEnemy($enemyId);
		$damageCount = $spell->getDamageAmount();
		$damageCount = self::applyBuffDamage($enemyObj, $damageCount);

		if ($damageCount > 0) {
			Details::damage($damageCount, $spell->getName());
			$spell->afterDamage($damageCount);
		}

		foreach ($spell->applyBuffs() as $applyBuff) {
			$enemyObj = $enemyObj->addBuff(clone $applyBuff);
		}

		self::cast($spell);
	}

	protected static function applyBuffDamage(Enemy $enemy, int $damageCount) {
		foreach ($enemy->getBuffs() as $buff) {
			/** @var $buff Buff */
			$damageCount = $buff->applyOnDamage($damageCount);
		}
		foreach (Player::getInstance()->getBuffs() as $buff) {
			/** @var $buff Buff */
			$damageCount = $buff->applyOnDamage($damageCount);
		}
		return $damageCount;
	}

}