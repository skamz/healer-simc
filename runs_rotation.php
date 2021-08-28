<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

function getCommand($rotation) {
	$phpPath = "F:/OpenServer/modules/php/PHP_7.4/php";
	if (!file_exists(dirname($phpPath))) {
		$phpPath = "php";
	}

	$script = __DIR__ . "/dps_rotations_checker.php";

	$command = "{$phpPath} {$script}";
	$command .= ' --rotation="' . $rotation . '"';
	return $command;
}

function doRotation($rotation, $iterationCount) {
	$execCommand = getCommand($rotation);

	$summary = [];
	for ($iter = 0; $iter < $iterationCount; $iter++) {
		$healAmount = WorkRotationManager::executeHealRotation($execCommand);

		$summary[] = $healAmount;
	}
	return array_sum($summary);
}

$rotationInfo = WorkRotationManager::fetchRotation();
$runCount = WorkRotationManager::getRunCount();
$totalRotationsHeal = doRotation($rotationInfo["rotation"], $runCount);
WorkRotationManager::storeResult($rotationInfo["id"], $totalRotationsHeal, $runCount);