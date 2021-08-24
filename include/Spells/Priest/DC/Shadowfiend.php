<?php


namespace Spells\Priest\DC;


use Pets\Pet;
use Spells\SpellSchool\Physical;

class Shadowfiend extends DcSpell {

	protected float $cd = 180;

	protected bool $hasteIsReduceGCd = true;
	protected string $spellSchool = Physical::class;
	protected bool $isDamageSpell = true;

	public function applySpecial() {
		$this->summonPet();
		//@todo action by leg
		//\Events::getInstance()->prolongBuffByName(Atonement::class, intval(self::PROLONG_BY_LEG / \TimeTicker::TICK_COUNT));
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Shadowfiend());
	}

	public function getDamageAmount() {
		return 0;
	}


}