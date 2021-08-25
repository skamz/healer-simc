<?php

ob_start();
require_once(__DIR__ . "/autoloader.php");

// Best rotation: 8 5 5 5 5 5 5
// Нужно 600 хасты (18% чтобы добрать до капа)
/**
 * Капы хасты:
 * 6 щитов = 110 хасты (3,3%)
 * 7 щитов =305 хасты (9,25%)
 * 8 щитов =555 хасты (18%)
 * 9 щитов =845 хасты
 * 10щитов =1190 хасты
 */

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

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

global $globalRotation;

$rotationInfoSteps = explode(" ", $globalRotation);

try {
	$rotation = new \Rotations\Priest\DC1();
	$rotation->run($rotationInfoSteps);
} catch (\Exceptions\CombatTimeDone $ex) {
} finally {

}

$atonementResult = intval(Details::getHealFromSpell(\Buffs\Priest\Atonement::class));
$log = ob_get_contents();
ob_end_clean();

echo json_encode(["atonement" => $atonementResult]);

