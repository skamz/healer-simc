<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class BoonOfAscended extends DcSpell {

	protected float $gcd = 1.5;
	protected float $castTime = 1.5;
	protected float $cd = 180;
	protected bool $isTriggeredAtonement = true;
	protected string $spellSchool = Arcane::class;

	public function applyBuffs(): array {
		$toPlayerModel = \Place::getInstance()->getMyPlayer();
		$toPlayerModel->addBuff(new \Buffs\Priest\BoonOfAscended());
		return [];
	}

}