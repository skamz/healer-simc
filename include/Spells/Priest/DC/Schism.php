<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Shadow;

class Schism extends DcSpell {

	protected float $manaPercentCost = 0.5;
	protected float $cd = 24;
	protected float $gcd = 1.5;
	protected float $castTime = 1.5;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Shadow::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = $this->applySecondary($return);
		return \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\Schism(),
		];
	}

	public function getRealDamageSPParams(): array {
		return [
			1087 => 1716,
			1000 => 1579,
			995 => 1571,
			979 => 1546,
			918 => 1449,
		];
	}

}