<?php


namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;

/**
 * https://www.wowdb.com/spells/17-power-word-shield
 * Class Power_Word_Shield
 * @package Spells\Priest
 */
class PowerWordShield extends DcSpell {

	protected float $castTime = 0;
	protected float $gcd = 1.5;
	protected float $manaPercentCost = 3.1;

	public function getHealAmount() {
		// add mastery if has Atonement
		$return = \Player::getInstance()->getInt() * 1.65;
		return $this->applySecondary($return);
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\Atonement(),
		];
	}

}

/**
 * $totalHeal = [
 * "base" => 4349614062,
 * "crit" => 4389402222,
 * "haste" => 4412580474,
 * "mastery" => 0,
 * "versa" => 4400981374,
 * ];
 */