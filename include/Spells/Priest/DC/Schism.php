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
	protected bool $isDamageSpell = true;

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
			1179 => 1662,
			1148 => 1619,
			1097 => 1547,
			1071 => 1510,
			1010 => 1424,
		];
	}

}