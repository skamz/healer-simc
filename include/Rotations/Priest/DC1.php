<?php


namespace Rotations\Priest;


use Rotations\BaseRotation;
use Spells\Priest\DC\Penance;
use Spells\Priest\DC\PowerWordRadiance;
use Spells\Priest\DC\Schism;
use Spells\Priest\DC\ShadowMend;
use Spells\Priest\PowerWordShield;

class DC1 extends BaseRotation {

	protected function fillSpellBook() {
		$this->addSpell(new Penance())
			->addSpell(new PowerWordShield())
			->addSpell(new Schism())
			->addSpell(new PowerWordRadiance())
			->addSpell(new ShadowMend());
	}

}