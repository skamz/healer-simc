<?php


namespace Spells\Priest\DC;


class PowerInfusion extends DcSpell {

	protected float $cd = 120;
	protected float $gcd = 0;

	public function getHealAmount() {
		return 0;
	}

	public function applyBuffs(): array {
		return [
			new \Buffs\Priest\PowerInfusion(),
		];
	}

}