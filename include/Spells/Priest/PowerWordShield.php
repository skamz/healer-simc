<?php


namespace Spells\Priest;

use Legendary\Priest\ClarityOfMind;
use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Holy;

/**
 * https://www.wowdb.com/spells/17-power-word-shield
 * Classes Power_Word_Shield
 * @package Spells\Priest
 */
class PowerWordShield extends DcSpell {

	protected float $castTime = 0;
	protected float $gcd = 1.5;
	protected float $manaPercentCost = 3.1;
	protected string $spellSchool = Holy::class;

	public function getHealAmount(): int {
		// add mastery if has Atonement
		$return = \Player::getInstance()->getInt() * 1.65;
		return $this->applySecondary($return);
	}

	public function applyBuffs(): array {
		$atonement = new \Buffs\Priest\Atonement();
		if (ClarityOfMind::isActive()) {
			$atonement->setDuration($atonement->getBaseDuration() + ClarityOfMind::INC_ATONEMENT_BUFF_TIME);
		}
		echo "atonement->Duration: " . $atonement->getDuration() . "<br>";
		return [
			$atonement
		];
	}

}