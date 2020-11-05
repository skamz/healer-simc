<?php


namespace Pets\Spells;


use Spells\Priest\DC\DcSpell;

class ShadowfiendAttack extends Spell {

	public function getDamageAmount() {
		return $this->attackDamage;
	}

}