<?php

namespace Rotations\Priest;

use Pets\Mindbender;
use Rotations\BaseRotation;
use Spells\Priest\DC\Halo;
use Spells\Priest\DC\MindBlast;
use Spells\Priest\DC\Penance;
use Spells\Priest\DC\PowerWordSolace;
use Spells\Priest\DC\Schism;
use Spells\Priest\Smite;

class DCKyrian extends BaseRotation {

	public function execute() {
		if ($this->isKnowSpell(Mindbender::class) and Mindbender::isAvailable()) {
			return Mindbender::class;
		} elseif (Schism::isAvailable()) {
			return Schism::class;
		} elseif (Penance::isAvailable()) {
			return Penance::class;
		} elseif ($this->isKnowSpell(Halo::class) && Halo::isAvailable()) {
			return Halo::class;
		} elseif ($this->isKnowSpell(PowerWordSolace::class) && PowerWordSolace::isAvailable()) {
			return PowerWordSolace::class;
		} elseif (MindBlast::isAvailable()) {
			return MindBlast::class;
		}
		return Smite::class;
	}

}