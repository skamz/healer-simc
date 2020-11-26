<?php

use Buffs\Priest\Atonement;

require_once(__DIR__ . "/autoloader.php");

const PENANCE = 1;
const SCHISM = 2;
const SMITE = 3;
const RADIANCE = 4;
const SHIELD = 5;
const MINDGAMES = 6;
const SOLACE = 7;
const PURGE_WICKED = 8;


$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 20;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();

$db = Database::getInstance();
$id = RedisManager::getInstance()->spop(RedisManager::ROTATIONS);

$rotationInfo = $db->query("select * from priest_dc_work where id = {$id} limit 1 ")->fetchArray();
if (empty($rotationInfo)) {
	exit;
}
print_r($rotationInfo);
$rotationInfoSteps = explode(" ", $rotationInfo["rotation"]);
$rotationVariables = [];
$maxCountAfterBuff = 10;
$currentAfterBuff = 0;
$buffCounter = 0;
$lastPurgeCast = 0;
$isEmptyBranch = false;

$secondsHeal = [];

//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		if (!empty($rotationInfoSteps)) {
			$nextSpell = current($rotationInfoSteps);
		} else {
			break;
		}
		if ($currentAfterBuff >= $maxCountAfterBuff) {
			break;
		}
		$secondNum = floor(TimeTicker::getInstance()->getCombatTimer());
		$secondsHeal[$secondNum] = intval(Place::getTotalHeal());
		if ($secondNum >= 4 && empty($secondsHeal[$secondNum])) {
			$isEmptyBranch = true;
			break;
		}

		$toPlayer = Place::getInstance()->getRandomNumPlayerWithoutBuff(Atonement::class);
		if (\Spells\Priest\DC\Schism::isAvailable() && $nextSpell == SCHISM) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\Penance::isAvailable() && $nextSpell == PENANCE) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\Smite::isAvailable() && $nextSpell == SMITE) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable() && $nextSpell == RADIANCE) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
		} elseif (\Spells\Priest\PowerWordShield::isAvailable() && $nextSpell == SHIELD) {
			Caster::castSpellToPlayer($toPlayer, $shield);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
			$buffCounter++;
		} elseif (\Spells\Priest\DC\Mindgames::isAvailable() && $nextSpell == MINDGAMES) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Mindgames());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PowerWordSolace::isAvailable() && $nextSpell == SOLACE) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PurgeWicked::isAvailable() && $nextSpell == PURGE_WICKED) {
			Caster::castSpellToEnemy($damageEnemy, $purgeWicked);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
			$lastPurgeCast = TimeTicker::getInstance()->getCombatTimer();
		}

	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
$maxPeriodHeal = Details::getMedianByPeriod();
echo "max median period: " . $maxPeriodHeal . "<br>\n";

$saveResult = [
	"id" => $rotationInfo["id"],
	"heal" => $maxPeriodHeal,
	"rotation" => $rotationInfo["rotation"],
];
RedisManager::getInstance()->sadd(RedisManager::SIM_RESULTS, json_encode($saveResult));
//$db->query("update priest_dc set total_heal = total_heal + {$totalResult}, iterations = iterations + 1, avg_heal = total_heal / iterations where id ={$rotationInfo["id"]}");

RedisManager::getInstance()->incr("sim_done");

if (Details::analyzePeakByMedian() === false || $isEmptyBranch || TimeTicker::getInstance()->getCombatTimer() >= $workTime || $currentAfterBuff >= $maxCountAfterBuff) {
	exit;
}

if (\Spells\Priest\DC\Schism::isAvailable()) {
	$rotationVariables[] = SCHISM;
}
if (\Spells\Priest\DC\Penance::isAvailable()) {
	$rotationVariables[] = PENANCE;
}
if (\Spells\Priest\DC\Mindgames::isAvailable()) {
	$rotationVariables[] = MINDGAMES;
}
if (\Spells\Priest\DC\PowerWordSolace::isAvailable()) {
	$rotationVariables[] = SOLACE;
}
if (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
	$rotationVariables[] = RADIANCE;
}

if (empty($rotationVariables)) {
	$rotationVariables[] = SMITE;
	if (TimeTicker::getInstance()->getCombatTimer() - $lastPurgeCast > 10) {
		$rotationVariables[] = PURGE_WICKED;
	}
}
if ($buffCounter < 7) {
	$rotationVariables[] = SHIELD;
}

$insertValues = [];
foreach ($rotationVariables as $nextSpell) {
	$testRotation = "{$rotationInfo["rotation"]} {$nextSpell}";
	RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, $testRotation);
}