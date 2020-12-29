<?php


namespace Spells\Priest\DC;


use Pets\Pet;

class Shadowfiend extends DcSpell {

	protected float $cd = 180;

	public function getDamageAmount() {
		$this->summonPet();
		return 0;
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Shadowfiend());
	}


}