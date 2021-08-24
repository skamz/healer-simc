<?php

namespace Spells\Priest\DC;

class Rapture extends DcSpell {

	public function applySpecial() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Priest\Rapture());
	}

}