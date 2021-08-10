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

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1094 => 921,
			1056 => 889,
			1012 => 852,
			986 => 830,
			979 => 824,

		];
	}

}