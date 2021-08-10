<?php


namespace Spells\Priest\DC;


use Spells\SpellSchool\Holy;

class PowerInfusion extends DcSpell {

	protected float $cd = 120;
	protected float $gcd = 0;
	protected string $spellSchool = Holy::class;

	public function getHealAmount() {
		return 0;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\PowerInfusion(),
		];
	}

}