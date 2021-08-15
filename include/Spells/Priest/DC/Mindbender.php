<?php


namespace Spells\Priest\DC;


use Buffs\Priest\Atonement;
use Spells\SpellSchool\Physical;

class Mindbender extends DcSpell {

	const PROLONG_BY_LEG = 3;
	protected float $cd = 60;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;
	protected string $spellSchool = Physical::class;
	protected bool $isDamageSpell = true;

	public function applySpecial() {
		$this->summonPet();
		\Events::getInstance()->prolongBuffByName(Atonement::class, intval(self::PROLONG_BY_LEG / \TimeTicker::TICK_COUNT));
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Mindbender());
	}

}