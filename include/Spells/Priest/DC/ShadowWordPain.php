<?php


namespace Spells\Priest\DC;


use Buffs\Priest\PowerDarkSide;
use Buffs\RealPPM;
use Spells\SpellSchool\Shadow;

class ShadowWordPain extends DcSpell {

	protected float $manaPercentCost = 1.8;
	protected float $gcd = 1.5;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Shadow::class;
	protected bool $isDamageSpell = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.209564;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\ShadowWordPain(),
		];
	}

	public function afterSuccessCast() {
		parent::afterSuccessCast();
		RealPPM::getInstance()->tryProc(1, new PowerDarkSide());
	}
}