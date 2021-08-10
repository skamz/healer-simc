<?php


namespace Spells\Priest\DC;


// Способность ковенантов Кирий
// Чтобы уметь ее применять надо сначала прожать спелл, чтобы получить баф
use Spells\SpellSchool\Arcane;

class AscendedBlast extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $gcd = 1;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 3;
	protected bool $hasteIsReduceCd = true;
	protected string $spellSchool = Arcane::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 1.79;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return);
		return $return;
	}
}