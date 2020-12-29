<?php


namespace Pets;


use Pets\Spells\MindbenderAttack;

class Mindbender extends Pet {

	protected float $liveTime = 12;

	public function tick() {
		parent::tick();
		if (MindbenderAttack::isAvailable()) {
			\Caster::applySpellToEnemy($this->getTargetNum(), new MindbenderAttack());
		}
	}

}