<?php

use Buffs\Priest\Atonement;
use Rotations\Priest\DC1;

require_once(__DIR__ . "/autoloader.php");

$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();
Helper::resetStats();

$workTime = 10;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$mindBlast = new \Spells\Priest\DC\MindBlast();
$mindbender = new \Spells\Priest\DC\Mindbender();
$halo = new \Spells\Priest\DC\Halo();


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


try {
	$rotation = new DC1();
	$rotation->run($rotationInfoSteps);
} catch (\Exceptions\PreventEndException $ex) {
	$preventEnd = true;
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