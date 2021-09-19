<?php

require_once(__DIR__ . "/autoloader.php");


$player = include(__DIR__ . "/player.php");

echo "Crit: " . Player::getInstance()->getStatCalculator()->getCritPercent() . "<br>\n";
echo "Haste: " . Player::getInstance()->getStatCalculator()->getHastePercent() . "<br>\n";
echo "Mastery: " . Player::getInstance()->getStatCalculator()->getMasteryPercent() . "<br>\n";
echo "Versa: " . Player::getInstance()->getStatCalculator()->getVersatilityPercent() . "<br>\n";
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

if (empty($_GET["r"])) {
	$_GET["r"] = "1 2 4 2 5 2 4 2 5 6 1 2 4 2 5 4 6 1 4";
}
$wasMoreAtonement = false;
$rotationInfoSteps = explode(" ", $_GET["r"]);

echo "Rotation: " . $_GET["r"] . "<br>\n";
$rotationVariables = [];

try {
	$rotation = new \Rotations\Paladin\Holy\LightOfMartyrRotation(new \Rotations\Paladin\Holy\PaladinSpells());
	$rotation->run($rotationInfoSteps);
} catch (\Exceptions\PreventEndException $ex) {
	$preventEnd = true;
}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";
echo "summary heal: " . Details::getSummaryHeal() . "<br>\n";

Details::printDamage();
Details::printHeal();

if (TimeTicker::getInstance()->getCombatTimer() >= $workTime) {
	exit("Time over");
}
/*
$spellList = [
	\Spells\Priest\BoonOfAscended::class,
	\Spells\Priest\AscendedBlast::class,
	\Spells\Priest\AscendedNova::class,
	\Spells\Priest\DC\MindBlast::class,
	\Spells\Priest\DC\Schism::class,
];
foreach ($spellList as $spellClass) {
	if ($spellClass::isAvailable()) {
		echo "{$spellClass} isAvailable<br>\n";
	} else {
		echo "{$spellClass} NOT Available<br>\n";
	}
}*/


