<?php


namespace Rotations\Priest;


use Buffs\Priest\Atonement;
use Caster;
use Exceptions\PreventEndException;
use Helper;
use Place;
use Rotations\BaseRotation;
use Spells\Priest\DC\Halo;
use Spells\Priest\DC\Mindbender;
use Spells\Priest\DC\MindBlast;
use Spells\Priest\DC\Mindgames;
use Spells\Priest\DC\Penance;
use Spells\Priest\DC\PowerWordRadiance;
use Spells\Priest\DC\PowerWordSolace;
use Spells\Priest\DC\PurgeWicked;
use Spells\Priest\DC\PurgeWickedDot;
use Spells\Priest\DC\Schism;
use Spells\Priest\DC\ShadowMend;
use Spells\Priest\DC\ShadowWordPain;
use Spells\Priest\PowerWordShield;
use Spells\Priest\Smite;
use TimeTicker;

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
	const MINDBENDER = 11;
	const SHADOW_PAIN = 12;

	private array $talents = [];

	public function setTalents($tier, $spell) {
		if (!empty($this->talents[$tier])) {
			exit("Талант не может быть выбран, уже взят другой из этой строки");
		}
		$this->talents[$tier] = true;
		$this->addSpell($spell);
	}

	protected function fillSpellBook() {
		$this->setTalents(3, new Mindbender());
		//$this->setTalents(3, new PowerWordSolace());
		$this->setTalents(6, new PurgeWicked());
		//$this->setTalents(6, new Halo());
	}

	public function getDotTimer() {

	}

	public function execute() {
		if ($this->isKnowSpell(Mindbender::class) and Mindbender::isAvailable()) {
			return Mindbender::class;
		} elseif (Schism::isAvailable()) {
			return Schism::class;
		} elseif (Mindgames::isAvailable()) {
			return Mindgames::class;
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

			case Mindbender::class:
				return self::MINDBENDER;

			case ShadowWordPain::class:
				return self::SHADOW_PAIN;
		}
		exit("неизвестный спелл");
	}

	public function run($rotationInfoSteps) {
		$shield = new  PowerWordShield();
		$penance = new Penance();
		$smite = new Smite();
		$radiance = new PowerWordRadiance();
		$solace = new PowerWordSolace();
		$purgeWicked = new PurgeWicked();
		$mindBlast = new MindBlast();
		$mindbender = new Mindbender();
		$halo = new Halo();

		$damageEnemy = Place::getInstance()->getRandomEnemy();
		$wasMoreAtonement = false;


		while (TimeTicker::getInstance()->tick()) {
			if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
				if (!empty($rotationInfoSteps)) {
					$nextSpell = current($rotationInfoSteps);
				} else {
					break;
				}
				$atonementCount = Helper::getCountPlayersWithBuff(Atonement::class);
				if ($atonementCount >= 10) {
					$wasMoreAtonement = true;
				}
				if ($wasMoreAtonement && $atonementCount <= 4) {
					echo "atonementCount={$atonementCount}. Break";
					throw new PreventEndException();
				}

				$toPlayer = Place::getInstance()->getRandomNumPlayerWithoutBuff(Atonement::class);
				if (Schism::isAvailable() && $nextSpell == DC1::SCHISM) {
					Caster::castSpellToEnemy($damageEnemy, new Schism());
					array_shift($rotationInfoSteps);
				} elseif (Penance::isAvailable() && $nextSpell == DC1::PENANCE) {
					Caster::castSpellToEnemy($damageEnemy, $penance);
					array_shift($rotationInfoSteps);
				} elseif (Smite::isAvailable() && $nextSpell == DC1::SMITE) {
					Caster::castSpellToEnemy($damageEnemy, $smite);
					array_shift($rotationInfoSteps);
				} elseif (PowerWordRadiance::isAvailable() && $nextSpell == DC1::RADIANCE) {
					Caster::castSpellToPlayer($toPlayer, $radiance);
					array_shift($rotationInfoSteps);
				} elseif (PowerWordShield::isAvailable() && $nextSpell == DC1::SHIELD) {
					Caster::castSpellToPlayer($toPlayer, $shield);
					array_shift($rotationInfoSteps);
				} elseif (Mindgames::isAvailable() && $nextSpell == DC1::MINDGAMES) {
					Caster::castSpellToEnemy($damageEnemy, new Mindgames());
					array_shift($rotationInfoSteps);
				} elseif (PowerWordSolace::isAvailable() && $nextSpell == DC1::SOLACE) {
					Caster::castSpellToEnemy($damageEnemy, $solace);
					array_shift($rotationInfoSteps);
				} elseif (PurgeWicked::isAvailable() && $nextSpell == DC1::PURGE_WICKED) {
					Caster::castSpellToEnemy($damageEnemy, $purgeWicked);
					array_shift($rotationInfoSteps);
				} elseif (MindBlast::isAvailable() && $nextSpell == DC1::MIND_BLAST) {
					Caster::castSpellToEnemy($damageEnemy, $mindBlast);
					array_shift($rotationInfoSteps);
				} elseif (Halo::isAvailable() && $nextSpell == DC1::HALO) {
					Caster::castSpellToEnemy($damageEnemy, $halo);
					array_shift($rotationInfoSteps);
				} elseif ($nextSpell == DC1::SHADOW_PAIN) {
					Caster::castSpellToEnemy($damageEnemy, $mindBlast);
					array_shift($rotationInfoSteps);
				} elseif (Mindbender::isAvailable() && $nextSpell == DC1::MINDBENDER) {
					Caster::castSpellToEnemy($damageEnemy, $mindbender);
					array_shift($rotationInfoSteps);
				}

			}

		}
	}

}