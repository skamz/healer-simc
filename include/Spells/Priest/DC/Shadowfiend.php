<?php


namespace Spells\Priest\DC;


use Pets\Pet;
use Spells\SpellSchool\Physical;

class Shadowfiend extends DcSpell {

	protected float $cd = 180;
	protected string $spellSchool = Physical::class;

	public function getDamageAmount() {
		$this->summonPet();
		return 0;
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Shadowfiend());
	}


}