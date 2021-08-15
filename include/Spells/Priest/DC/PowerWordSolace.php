<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Holy;

class PowerWordSolace extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected float $cd = 15;
	protected bool $hasteIsReduceCd = true;
	protected float $travelTime = 0.5;
	protected string $spellSchool = Holy::class;
	protected bool $isDamageSpell = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1179 => 886,
			1148 => 863,
			1097 => 825,
			1071 => 805,
			1010 => 759,
		];
	}

}