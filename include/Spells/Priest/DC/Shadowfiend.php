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
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Shadowfiend());
	}

	public function getDamageAmount() {
		return 0;
	}


}