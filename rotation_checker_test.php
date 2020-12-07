<?php

require_once(__DIR__ . "/autoloader.php");

const PENANCE = 1;
const SCHISM = 2;
const SMITE = 3;
const RADIANCE = 4;
const SHIELD = 5;
const MINDGAMES = 6;
const SOLACE = 7;
const PURGE_WICKED = 8;
const MIND_BLAST = 9;


$player = include(__DIR__ . "/player.php");
Player::getInstance()->setInt(1277)
	->setCrit(327)
	->setHaste(408)
	->setVersatility(229)
	->setMatery(432);
/*
Player::getInstance()->setInt(733)
	->setCrit(239)
	->setHaste(298)
	->setVersatility(0)
	->setMatery(393);
*/
echo "hast percent: " . Player::getInstance()->getStatCalculator()->getHastePercent() . "<br>";
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 30;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$mindBlast = new \Spells\Priest\DC\MindBlast();

global $globalRotation;

if (empty($_GET["r"])) {
	$_GET["r"] = "1 1 1 8 5 5 5 5 5 5 4 4 2 6 1 7 3 3 3 3 3 3 3 1 3 7";
}
$wasMoreAtonement = false;
$rotationInfoSteps = explode(" ", $_GET["r"]);

echo "Rotation: " . $_GET["r"] . "<br>\n";
$rotationVariables = [];
$maxCountAfterBuff = 9;
$currentAfterBuff = 0;
$preventEnd = false;

$rotationsGenerator = new \Rotations\Priest\DC1();

//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		$atonementCount = Helper::getCountPlayersWithBuff(\Buffs\Priest\Atonement::class);
		if ($atonementCount >= 10) {
			$wasMoreAtonement = true;
		}
		if ($wasMoreAtonement && $atonementCount <= 4) {
			echo "atonementCount={$atonementCount}. Break";
			$preventEnd = true;
			break;
		}
		echo "Next by gen: " . $rotationsGenerator->execute() . "<br>";

		if (!empty($rotationInfoSteps)) {
			$nextSpell = current($rotationInfoSteps);
		} else {
			break;
		}


		$toPlayer = Place::getInstance()->getRandomNumPlayerWithoutBuff(\Buffs\Priest\Atonement::class);
		if (\Spells\Priest\DC\Schism::isAvailable() && $nextSpell == SCHISM) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\DC\Penance::isAvailable() && $nextSpell == PENANCE) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\Smite::isAvailable() && $nextSpell == SMITE) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable() && $nextSpell == RADIANCE) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
		} elseif (\Spells\Priest\PowerWordShield::isAvailable() && $nextSpell == SHIELD) {
			Caster::castSpellToPlayer($toPlayer, $shield);
			array_shift($rotationInfoSteps);
			$currentAfterBuff = 0;
		} elseif (\Spells\Priest\DC\Mindgames::isAvailable() && $nextSpell == MINDGAMES) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Mindgames());
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\DC\PowerWordSolace::isAvailable() && $nextSpell == SOLACE) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\DC\PurgeWicked::isAvailable() && $nextSpell == PURGE_WICKED) {
			Caster::castSpellToEnemy($damageEnemy, $purgeWicked);
			array_shift($rotationInfoSteps);
			$currentAfterBuff++;
		} elseif (\Spells\Priest\DC\MindBlast::isAvailable() && $nextSpell == MIND_BLAST) {
			Caster::castSpellToEnemy($damageEnemy, $mindBlast);
			array_shift($rotationInfoSteps);
		}

	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
echo "summary heal: " . Details::getSummaryHeal() . "<br>\n";

Details::printDamage();
Details::printHeal();
echo "Atonement heal: " . Details::getHealFromSpell(\Buffs\Priest\Atonement::class) . "<br>\n";
echo "max median period: " . Details::getMedianByPeriod() . "<br>\n";
Details::analyzePeakByMedian();

if($preventEnd){
	exit("GG");
}

$rotationsGenerator = new \Rotations\Priest\DC1();
$nextSpellName = $rotationsGenerator->execute();
$nextSpellNum = $rotationsGenerator->getSpellNum($nextSpellName);
echo "Next spell {$nextSpellName} ({$nextSpellNum})\n";

exit;
$saveResult = [
	"id" => $rotationInfo["id"],
	"heal" => $totalResult,
	"rotation" => $rotationInfo["rotation"],
];
RedisManager::getInstance()->sadd(RedisManager::SIM_RESULTS, json_encode($saveResult));
//$db->query("update priest_dc set total_heal = total_heal + {$totalResult}, iterations = iterations + 1, avg_heal = total_heal / iterations where id ={$rotationInfo["id"]}");

RedisManager::getInstance()->incr("sim_done");

if (TimeTicker::getInstance()->getCombatTimer() >= $workTime) {
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
if (empty($rotationVariables)) {
	$rotationVariables[] = SMITE;
	$rotationVariables[] = PURGE_WICKED;
}


print_r($rotationVariables);
$insertValues = [];
foreach ($rotationVariables as $nextSpell) {
	$testRotation = "{$rotationInfo["rotation"]} {$nextSpell}";
	//$row = $db->query("select 1 from 	priest_dc_future where rotation='{$testRotation}' limit 1")->fetchArray();
	//if (empty($row)) {
	RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, $testRotation);
	//$insertValues[] = $testRotation;
	//}
}
if (!empty($insertValues)) {
	$db->query("insert ignore into priest_dc(rotation) values('" . implode("'), ('", $insertValues) . "')");
}