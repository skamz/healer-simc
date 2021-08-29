<?php

ob_start();
require_once(__DIR__ . "/autoloader.php");

/**
 * Капы хасты:
 * 6 щитов = 110 хасты (3,3%)
 * 7 щитов =305 хасты (9,25%)
 * 8 щитов =555 хасты (18%)
 * 9 щитов =845 хасты
 * 10щитов =1190 хасты
 */

$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

global $globalRotation;

$rotationInfoSteps = explode(" ", $globalRotation);

try {
	$rotation = new \Rotations\BaseRotation();
	$rotation->run($rotationInfoSteps);
} catch (Exception $ex) {
} finally {
	$atonementResult = intval(Details::getHealFromSpell(\Buffs\Priest\Atonement::class));
	$log = ob_get_contents();
	ob_end_clean();

	echo json_encode(["atonement" => $atonementResult]);
}



