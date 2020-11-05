<?php


namespace Pets\Spells;


use Spells\Priest\DC\DcSpell;

class Spell extends DcSpell {

	protected int $attackDamage;

	public function getAttackDamage(int $damage) {
		$this->attackDamage = $damage;
	}

}