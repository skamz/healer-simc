<?php
// show rotation: 5 5 5 5 5 5 5 5 4 4 13 14 17 15 2 15 15 15 14 9 15 14
ob_start();
require_once(__DIR__ . "/autoloader.php");

function sendResult() {
	$log = ob_get_contents();
	ob_end_clean();
	$atonementResult = intval(Details::getHealFromSpell(\Buffs\Priest\Atonement::class));
	echo json_encode(["atonement" => $atonementResult]);
}

$args = getopt("", ["type:", "rotation:"]);
if (empty($args["rotation"])) {
	echo "Rotations no set\n";
	exit(1);
}

$player = include(__DIR__ . "/player.php");
$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$rotationInfoSteps = explode(" ", $args["rotation"]);

try {
	$rotation = new \Rotations\BaseRotation();
	$rotation->run($rotationInfoSteps);
} catch (Exception $ex) {
} finally {
	sendResult();
}


if (TimeTicker::getInstance()->getCombatTimer() >= $workTime) {
	Details::log("Time out");
	exit;
}
try {
	Details::log("Try extend rotation");
	$rotation->waitAvailableCast();
	$spellList = [
		\Spells\Priest\BoonOfAscended::class,
		\Spells\Priest\AscendedBlast::class,
		\Spells\Priest\AscendedNova::class,
		\Spells\Priest\DC\MindBlast::class,
		\Spells\Priest\DC\Schism::class,
	];
	WorkRotationManager::addSpells($spellList, trim($args["rotation"]));
} catch (Exception $ex) {
}
$log = ob_get_contents();
ob_end_clean();
