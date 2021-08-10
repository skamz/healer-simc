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
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1087 => 907,
			1061 => 886,
			1056 => 881,
			1000 => 835,
			979 => 817,
		];
	}

	public function getRealHealSPParams(): array {
		return [
			1087 => 3261,
			1061 => 3183,
			1056 => 3168,
			1000 => 3000,
			979 => 2937,
		];
	}
}