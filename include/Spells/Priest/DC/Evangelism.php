<?php

namespace Spells\Priest\DC;

use Buffs\Priest\Atonement;
use Spells\SpellSchool\Holy;

class Evangelism extends DcSpell {

	const PROLONG_ATONEMENT = 6;
	protected float $cd = 90;
	protected string $spellSchool = Holy::class;

	public function applySpecial() {
		\Events::getInstance()->prolongBuffByName(Atonement::class, intval(self::PROLONG_ATONEMENT / \TimeTicker::TICK_COUNT));
	}

}