<?php


namespace Pets;


use Pets\Spells\ShadowfiendAttack;

class Shadowfiend extends Pet {

	protected float $liveTime = 15;

	public function tick() {
		parent::tick();
		if (ShadowfiendAttack::isAvailable()) {
			\Caster::applySpellToEnemy($this->getTargetNum(), new ShadowfiendAttack());
		}
	}

}