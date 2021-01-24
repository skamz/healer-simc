<?php


namespace Spells\Priest\DC;


use Buffs\Priest\Atonement;

class Mindbender extends DcSpell {

	const PROLONG_BY_LEG = 3;
	protected float $cd = 60;
	protected float $gcd = 1.5;
	protected bool $hasteIsReduceGCd = true;

	public function getDamageAmount() {
		$this->summonPet();
		\Events::getInstance()->prolongBuffByName(Atonement::class, intval(self::PROLONG_BY_LEG / \TimeTicker::TICK_COUNT));
		return 0;
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Mindbender());
	}

}