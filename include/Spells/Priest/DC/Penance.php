<?php

namespace Spells\Priest\DC;

use Buffs\Priest\PowerDarkSide;
use Spells\SpellSchool\Holy;

/**
 * Class Penance https://www.wowhead.com/spell=172098/penance
 * @package Spells\Priest\DC
 */
class Penance extends DcSpell {

	protected float $cd = 9;
	protected float $castTime = 2;
	protected bool $hasteIsReduceCd = false;
	protected bool $hasteIsReduceDuration = true;
	protected bool $hasteIsReduceCastTime = true;
	protected float $manaPercentCost = 1.6;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Holy::class;
	protected bool $isDamageSpell = true;

	public function getDamageAmount() {
		$return = 0;
		for ($i = 0; $i < 3; $i++) {
			$tick = $this->getTickDamageAmount();
			$return += $tick;
		}
		return $return;
	}

	protected function getTickDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getHealAmount(): int {
		$return = 0;
		for ($i = 0; $i < 3; $i++) {
			$tick = $this->getTickHealAmount();
			$return += $tick;
		}
		return round($return);
	}

	protected function getTickHealAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_HEAL);
		return $this->applySecondary($return);
	}

	public function afterSuccessCast() {
		parent::afterSuccessCast();
		\Place::getInstance()->getMyPlayer()->removeBuff(PowerDarkSide::class);
	}

	public function getRealDamageSPParams(): array {
		return [
			1179 => 1329 / 3,
			1148 => 1294 / 3,
			1097 => 1237 / 3,
			1071 => 1208 / 3,
			1010 => 1139 / 3,

		];
	}

	public function getRealHealSPParams(): array {
		return [
			1082 => 1352,
			1061 => 1326,
			1056 => 1320,
			1000 => 1250,
			979 => 1223,
		];
	}

}
