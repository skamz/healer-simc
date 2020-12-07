<?php


namespace Rotations\Priest;


use Rotations\BaseRotation;
use Spells\Priest\DC\Halo;
use Spells\Priest\DC\MindBlast;
use Spells\Priest\DC\Mindgames;
use Spells\Priest\DC\Penance;
use Spells\Priest\DC\PowerWordRadiance;
use Spells\Priest\DC\PowerWordSolace;
use Spells\Priest\DC\PurgeWicked;
use Spells\Priest\DC\PurgeWickedDot;
use Spells\Priest\DC\Schism;
use Spells\Priest\DC\ShadowMend;
use Spells\Priest\PowerWordShield;
use Spells\Priest\Smite;

class DC1 extends BaseRotation {

	const PENANCE = 1;
	const SCHISM = 2;
	const SMITE = 3;
	const RADIANCE = 4;
	const SHIELD = 5;
	const MINDGAMES = 6;
	const SOLACE = 7;
	const PURGE_WICKED = 8;
	const MIND_BLAST = 9;
	const HALO = 10;


	private array $talents = [];

	public function setTalents($tier, $spell) {
		if (!empty($this->talents[$tier])) {
			exit("Талант не может быть выбран, уже взят другой из этой строки");
		}
		$this->talents[$tier] = true;
		$this->addSpell($spell);
	}

	protected function fillSpellBook() {
		$this->setTalents(3, new PowerWordSolace());
		$this->setTalents(6, new PurgeWicked());
	}

	public function getDotTimer() {

	}

	public function execute() {
		if (Schism::isAvailable()) {
			return Schism::class;
		} elseif (Mindgames::isAvailable()) {
			return Mindgames::class;
		} elseif ($this->isKnowSpell(Halo::class) && Halo::isAvailable()) {
			return Halo::class;
		} elseif (Penance::isAvailable()) {
			return Penance::class;
		} elseif ($this->isKnowSpell(PowerWordSolace::class) && PowerWordSolace::isAvailable()) {
			return PowerWordSolace::class;
		} elseif (MindBlast::isAvailable()) {
			return MindBlast::class;
		}
		return Smite::class;
	}


	public function getSpellNum($spellName) {
		switch ($spellName) {
			case Schism::class:
				return self::SCHISM;

			case Mindgames::class:
				return self::MINDGAMES;

			case Halo::class:
				return self::HALO;

			case Penance::class:
				return self::PENANCE;

			case PowerWordSolace::class:
				return self::SOLACE;

			case MindBlast::class:
				return self::MIND_BLAST;

			case Smite::class:
				return self::SMITE;
		}
		exit("неизвестный спелл");
	}

}