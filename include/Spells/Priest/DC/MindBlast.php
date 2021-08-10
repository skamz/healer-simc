<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Shadow;

class MindBlast extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 15;
	protected bool $hasteIsReduceCd = true;
	protected string $spellSchool = Shadow::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.7452634248;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}
}