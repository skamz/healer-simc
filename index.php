<?php
require_once(__DIR__ . "/rotation_checker_test.php");exit();

/**
 * Best rotation:
 * 8 5 5 5 5 5 5 4 4 2 6 1 7 9 3
 */

require_once(__DIR__ . "/autoloader.php");

$player = include(__DIR__ . "/player.php");

$damageEnemy = Place::getInstance()->getRandomEnemy();

echo "Crit: " . Player::getInstance()->getStatCalculator()->getCritPercent() . "\n";
echo "Haste: " . Player::getInstance()->getStatCalculator()->getHastePercent() . "\n";
echo "Mastery: " . Player::getInstance()->getStatCalculator()->getMasteryPercent() . "\n";
echo "Versa: " . Player::getInstance()->getStatCalculator()->getVersatilityPercent() . "\n";

TimeTicker::getInstance()->getTotalWorkTime(40);

echo "<hr>";

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$schism = new \Spells\Priest\DC\Schism();
$mindgames = new \Spells\Priest\DC\Mindgames();

$isApply = false;
$toPlayer = Place::getInstance()->getRandomNumPlayer();

Caster::castSpellToEnemy($damageEnemy, $purgeWicked);

$num = 0;

//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		if ($num == 0 && \Spells\Priest\Smite::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			$num++;
		} elseif ($num == 1 && \Spells\Priest\PowerWordShield::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $shield);
			$num++;
		} elseif ($num == 2 && \Spells\Priest\DC\Penance::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			$num++;
		} elseif ($num == 3 && \Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			$num++;
		} elseif ($num == 4 && \Spells\Priest\DC\PowerWordSolace::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			$num++;
		} elseif ($num == 5 && \Spells\Priest\DC\Schism::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $schism);
			$num++;
		} elseif ($num == 6 && \Spells\Priest\DC\Mindgames::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $mindgames);
			$num++;
		}

		//$playerNum = Place::getInstance()->getRandomNumPlayer();
		//if (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
		//	Caster::castSpellToPlayer($playerNum, $radiance);
		//}
		/*if (\Spells\Priest\DC\PurgeWicked::isAvailable() && \Buffs\Priest\PurgeWicked::isTimeRefreshBuffOnEnemy($damageEnemy)) {
			Caster::castSpellToEnemy($damageEnemy, $purgeWicked);
		} elseif (\Spells\Priest\DC\Penance::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
		} elseif (\Spells\Priest\Smite::isAvailable() && $smiteCount++ < 10) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
		}*/


		/*
		$toPlayer = Place::getInstance()->getRandomNumPlayer();

		if (\Spells\Priest\DC\Schism::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotation);
		} elseif (\Spells\Priest\DC\Penance::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
		} elseif (\Spells\Priest\Smite::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
		} elseif (\Spells\Priest\PowerWordShield::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $shield);
		}
		*/
	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
