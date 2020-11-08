<?php


namespace Spells\Priest\DC;


use Pets\Pet;

class Shadowfiend extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 180;
	protected bool $hasteIsReduceCd = true;

	public function getDamageAmount() {
		$this->summonPet();
		return 0;
	}

	protected function summonPet() {
		\Place::getInstance()->addPet(new \Pets\Shadowfiend());
	}


}