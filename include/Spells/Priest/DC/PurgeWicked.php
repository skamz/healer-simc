<?php


namespace Spells\Priest\DC;


use Buffs\Priest\PowerDarkSide;
use Buffs\RealPPM;
use Spells\SpellSchool\Fire;

/**
 * Class PurgeWicked Очищение зла https://www.wowhead.com/spell=204197/purge-the-wicked
 * @package Spells\Priest\DC
 */
class PurgeWicked extends DcSpell {

	protected float $manaPercentCost = 1.8;
	protected float $gcd = 1.5;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Fire::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.209564;
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
		parent::afterSuccessCast();
		RealPPM::getInstance()->tryProc(1, new PowerDarkSide());
	}
}