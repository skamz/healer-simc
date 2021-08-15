<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Holy;

class Halo extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 40;
	protected string $spellSchool = Holy::class;
	protected bool $isDamageSpell = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.9677858903;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}