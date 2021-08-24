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
const MINDBENDER = 10;


$player = include(__DIR__ . "/player.php");
/*Player::getInstance()//->setInt(1277)
	//->setCrit(327)
	->setHaste(676);
	//->setVersatility(229)
	//->setMatery(432);



Player::getInstance()
	->setInt(929)
	->setCrit(239)
	->setHaste(298)
	->setVersatility(0)
	->setMatery(393);
*/
echo "Crit: " . Player::getInstance()->getStatCalculator()->getCritPercent() . "<br>\n";
echo "Haste: " . Player::getInstance()->getStatCalculator()->getHastePercent() . "<br>\n";
echo "Mastery: " . Player::getInstance()->getStatCalculator()->getMasteryPercent() . "<br>\n";
echo "Versa: " . Player::getInstance()->getStatCalculator()->getVersatilityPercent() . "<br>\n";
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();
$purgeWicked = new \Spells\Priest\DC\PurgeWicked();
$mindBlast = new \Spells\Priest\DC\MindBlast();
$mindbender = new \Spells\Priest\DC\Mindbender();

global $globalRotation;

if (empty($_GET["r"])) {
	$_GET["r"] = "8 5 5 5 5 5 5 5 5 5 4 4 11 2 6 1 9 3 3 3 3 3 3";
}
$wasMoreAtonement = false;
$rotationInfoSteps = explode(" ", $_GET["r"]);

echo "Rotation: " . $_GET["r"] . "<br>\n";
$rotationVariables = [];
$maxCountAfterBuff = 9;
$currentAfterBuff = 0;
$preventEnd = false;


try {
	$rotation = new \Rotations\Priest\DCKyrian();
	$rotation->run($rotationInfoSteps);
} catch (\Exceptions\PreventEndException $ex) {
	$preventEnd = true;
}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
echo "summary heal: " . Details::getSummaryHeal() . "<br>\n";

Details::printDamage();
Details::printHeal();
echo "Atonement heal: " . Details::getHealFromSpell(\Buffs\Priest\Atonement::class) . "<br>\n";
echo "max median period: " . Details::getMedianByPeriod() . "<br>\n";
Details::analyzePeakByMedian();

if ($preventEnd) {
	exit("GG");
}

