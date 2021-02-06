<?php

namespace Spells\Priest\DC;

use Buffs\Priest\PowerDarkSide;

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

	public function getDamageAmount() {
		$return = 0;
		for ($i = 0; $i < 3; $i++) {
			$tick = $this->getTickDamageAmount();
			$return += $tick;
		}
		return $return;
	}

	protected function getTickDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.376;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getHealAmount() {
		$return = 0;
		for ($i = 0; $i < 3; $i++) {
			$tick = $this->getTickHealAmount();
			echo __CLASS__ . " tick heal {$tick}<br>\n";
			$return += $tick;
		}
		return $return;
	}

	protected function getTickHealAmount() {
		$return = \Player::getInstance()->getInt() * 1.25;
		return $this->applySecondary($return);
	}

	public function afterSuccessCast() {
		parent::afterSuccessCast();
		\Place::getInstance()->getMyPlayer()->removeBuff(PowerDarkSide::class);
	}

}
