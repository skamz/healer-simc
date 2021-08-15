<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Shadow;

/**
 * Class ShadowMend https://www.wowdb.com/spells/186263-shadow-mend
 * @package Spells\Priest\DC
 */
class ShadowMend extends DcSpell {

	protected float $manaPercentCost = 3.5;
	protected float $castTime = 1.5;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceCastTime = true;
	protected string $spellSchool = Shadow::class;

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 3.2;
		return $this->applySecondary($return);
	}

	public function getDotAmount() {
		return $this->getHealAmount() / 2;
	}

}