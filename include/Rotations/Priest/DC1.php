<?php


namespace Rotations\Priest;


use Buffs\Priest\Atonement;
use Buffs\Priest\SinsMany;
use Caster;
use Exceptions\PreventEndException;
use Helper;
use Place;
use Rotations\BaseRotation;
use Spells\Priest\AscendedBlast;
use Spells\Priest\AscendedNova;
use Spells\Priest\BoonOfAscended;
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

// 2 6 1 7 9 3
class DC1 extends BaseRotation {

	protected function fillSpellBook() {
		$this->setTalents(3, new Mindbender());
		//$this->setTalents(3, new PowerWordSolace());
		//$this->setTalents(5, new \Spells\Priest\DC\SinsMany());
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


	/**
	 * @param $rotationInfoSteps
	 * @throws PreventEndException
	 * @deprecated
	 */
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
				if (Schism::isAvailable() && $nextSpell == DCSpells::SCHISM) {
					Caster::playerCastSpell($damageEnemy, new Schism(), true);
					array_shift($rotationInfoSteps);
				} elseif (Penance::isAvailable() && $nextSpell == DCSpells::PENANCE) {
					Caster::playerCastSpell($damageEnemy, $penance, true);
					array_shift($rotationInfoSteps);
				} elseif (Smite::isAvailable() && $nextSpell == DCSpells::SMITE) {
					Caster::playerCastSpell($damageEnemy, $smite, true);
					array_shift($rotationInfoSteps);
				} elseif (PowerWordRadiance::isAvailable() && $nextSpell == DCSpells::RADIANCE) {
					Caster::playerCastSpell($toPlayer, $radiance, false);
					array_shift($rotationInfoSteps);
				} elseif (PowerWordShield::isAvailable() && $nextSpell == DCSpells::SHIELD) {
					Caster::playerCastSpell($toPlayer, $shield, false);
					array_shift($rotationInfoSteps);
				} elseif (Mindgames::isAvailable() && $nextSpell == DCSpells::MINDGAMES) {
					Caster::playerCastSpell($damageEnemy, new Mindgames(), true);
					array_shift($rotationInfoSteps);
				} elseif (PowerWordSolace::isAvailable() && $nextSpell == DCSpells::SOLACE) {
					Caster::playerCastSpell($damageEnemy, $solace, true);
					array_shift($rotationInfoSteps);
				} elseif (PurgeWicked::isAvailable() && $nextSpell == DCSpells::PURGE_WICKED) {
					Caster::playerCastSpell($damageEnemy, $purgeWicked, true);
					array_shift($rotationInfoSteps);
				} elseif (MindBlast::isAvailable() && $nextSpell == DCSpells::MIND_BLAST) {
					Caster::playerCastSpell($damageEnemy, $mindBlast, true);
					array_shift($rotationInfoSteps);
				} elseif (Halo::isAvailable() && $nextSpell == DCSpells::HALO) {
					Caster::playerCastSpell($damageEnemy, $halo, true);
					array_shift($rotationInfoSteps);
				} elseif ($nextSpell == DCSpells::SHADOW_PAIN) {
					Caster::playerCastSpell($damageEnemy, new ShadowWordPain(), true);
					array_shift($rotationInfoSteps);
				} elseif (Mindbender::isAvailable() && $nextSpell == DCSpells::MINDBENDER) {
					Caster::playerCastSpell($damageEnemy, $mindbender, true);
					array_shift($rotationInfoSteps);
				}

			}

		}
	}

}