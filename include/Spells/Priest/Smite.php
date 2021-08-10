<?php


namespace Spells\Priest;


use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Holy;

/**
 * Class Smite https://www.wowhead.com/spell=585/smite
 * @package Spells\Priest
 */
class Smite extends DcSpell {

	protected float $manaPercentCost = 0.2;
	protected float $castTime = 1.5;
	protected float $gcd = 1.5;
	protected float $cd = 0;
	protected bool $isTriggeredAtonement = true;
	protected bool $hasteIsReduceGCd = true;
	protected bool $hasteIsReduceCastTime = true;
	protected bool $hasteIsReduceCd = true;
	protected string $spellSchool = Holy::class;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE); // 47% base; +50% rank2 -25% (id=137032;Passive, Hidden)
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1087 => 605,
			1082 => 602,
			1061 => 590,
			1056 => 588,
			1000 => 556,
		];
	}

}