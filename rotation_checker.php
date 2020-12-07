<?php

use Buffs\Priest\Atonement;
use Rotations\Priest\DC1;

require_once(__DIR__ . "/autoloader.php");


$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();
Helper::resetStats();

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$mindBlast = new \Spells\Priest\DC\MindBlast();

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
$wasMoreAtonement = false;
$preventEnd = false;


//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		echo "Next step: {$nextSpell}; lost step: " . implode(" ", $rotationInfoSteps) . " \n";
		if (!empty($rotationInfoSteps)) {
			$nextSpell = current($rotationInfoSteps);
		} else {
			break;
		}
		/*if ($currentAfterBuff >= $maxCountAfterBuff) {
			break;
		}*/
		$atonementCount = Helper::getCountPlayersWithBuff(\Buffs\Priest\Atonement::class);
		if ($atonementCount >= 10) {
			$wasMoreAtonement = true;
		}
		if ($wasMoreAtonement && $atonementCount <= 4) {
			echo "atonementCount={$atonementCount}. Break";
			$preventEnd = true;
			break;
		}

		$toPlayer = Place::getInstance()->getRandomNumPlayerWithoutBuff(Atonement::class);
		if (\Spells\Priest\DC\Schism::isAvailable() && $nextSpell == DC1::SCHISM) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\Penance::isAvailable() && $nextSpell == DC1::PENANCE) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\Smite::isAvailable() && $nextSpell == DC1::SMITE) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable() && $nextSpell == DC1::RADIANCE) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
		} elseif (\Spells\Priest\PowerWordShield::isAvailable() && $nextSpell == DC1::SHIELD) {
			Caster::castSpellToPlayer($toPlayer, $shield);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
			$buffCounter++;
		} elseif (\Spells\Priest\DC\Mindgames::isAvailable() && $nextSpell == DC1::MINDGAMES) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Mindgames());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PowerWordSolace::isAvailable() && $nextSpell == DC1::SOLACE) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
		} elseif (\Spells\Priest\DC\PurgeWicked::isAvailable() && $nextSpell == DC1::PURGE_WICKED) {
			Caster::castSpellToEnemy($damageEnemy, $purgeWicked);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
			$buffCounter = 0;
			$lastPurgeCast = TimeTicker::getInstance()->getCombatTimer();
		} elseif (\Spells\Priest\DC\MindBlast::isAvailable() && $nextSpell == DC1::MIND_BLAST) {
			Caster::castSpellToEnemy($damageEnemy, $mindBlast);
			array_shift($rotationInfoSteps);
		}

	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
$maxPeriodHeal = Details::getMedianByPeriod();
echo "max median period: " . $maxPeriodHeal . "<br>\n";

$atonementResult = intval(Details::getHealFromSpell(Atonement::class));
echo "Atonement heal: " . $atonementResult . "<br>\n";

// нужно сохранять максимум от хила Atonement

$saveResult = [
	"id" => $rotationInfo["id"],
	"heal" => $atonementResult,
	"rotation" => $rotationInfo["rotation"],
	"time" => TimeTicker::getInstance()->getCombatTimer(),
];
RedisManager::getInstance()->sadd(RedisManager::SIM_RESULTS, json_encode($saveResult));
//$db->query("update priest_dc set total_heal = total_heal + {$totalResult}, iterations = iterations + 1, avg_heal = total_heal / iterations where id ={$rotationInfo["id"]}");

RedisManager::getInstance()->incr("sim_done");

// if Atonement <= 6 - break. Это нужно чтобы выбрать ротацию с минимальным промежутком времени


if (Details::analyzePeakByMedian() === false || $isEmptyBranch || TimeTicker::getInstance()->getCombatTimer() >= $workTime || $currentAfterBuff >= $maxCountAfterBuff) {
	//exit;
}

if (TimeTicker::getInstance()->getCombatTimer() >= $workTime || $preventEnd) {
	$out = "Is end work";
	if ($preventEnd) {
		$out .= " by preventEnd";
	}
	exit("Is end");
}


$rotationsGenerator = new DC1();
$nextSpellName = $rotationsGenerator->execute();
$nextSpellNum = $rotationsGenerator->getSpellNum($nextSpellName);
echo "Next spell {$nextSpellName} ({$nextSpellNum})\n";

$rotationVariables[] = $nextSpellNum;
if (TimeTicker::getInstance()->getCombatTimer() - $lastPurgeCast > 10) {
	//$rotationVariables[] = DC1::PURGE_WICKED;
}


$insertValues = [];
foreach ($rotationVariables as $nextSpell) {
	$testRotation = "{$rotationInfo["rotation"]} {$nextSpell}";
	echo "add rotation: " . $testRotation . "\n";
	RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, $testRotation);
}