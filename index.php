<?php

require_once(__DIR__ . "/autoloader.php");

$player = include(__DIR__ . "/player.php");

$damageEnemy = Place::getInstance()->getRandomEnemy();

TimeTicker::getInstance()->getTotalWorkTime(40);

echo "<hr>";

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$solace = new \Spells\Priest\DC\PowerWordSolace();

$isApply = false;


//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		if (\Spells\Priest\DC\PowerWordSolace::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
		}
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
