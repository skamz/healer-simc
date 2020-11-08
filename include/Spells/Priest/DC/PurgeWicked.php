<?php


namespace Spells\Priest\DC;


use Buffs\Priest\PowerDarkSide;
use Buffs\RealPPM;

/**
 * Class PurgeWicked Очищение зла https://www.wowhead.com/spell=204197/purge-the-wicked
 * @package Spells\Priest\DC
 */
class PurgeWicked extends DcSpell {

	protected float $manaPercentCost = 1.8;
	protected float $gcd = 1.5;
	protected bool $isTriggeredAtonement = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.248;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\PurgeWicked(),
		];
	}

	public function afterSuccessCast() {
		RealPPM::getInstance()->tryProc(1, new PowerDarkSide());
	}
}