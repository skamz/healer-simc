<?php

require_once(__DIR__ . "/autoloader.php");

function storeResult($id) {
	$damage = (int)Details::getTotalDamage();
	Database::getInstance()->query("update priest_dc_work set total_heal=total_heal+{$damage}, iterations = iterations + 1 where id = {$id} limit 1 ");
	echo "total damage: {$damage}<br>\n";
}

function addSpells(array $spellList, string $rotation) {
	$return = [];
	foreach ($spellList as $spellClass) {
		if ($spellClass::isAvailable()) {
			$spellNum = \Rotations\Priest\DCSpells::getSpellNum($spellClass);
			$return[] = trim("{$rotation} {$spellNum}");
		}
	}
	registerRotations($return);
}

function registerRotations(array $rotations) {
	foreach ($rotations as $testRotation) {
		echo "add rotation: " . $testRotation . "\n";
		RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, $testRotation);
	}
}

$player = include(__DIR__ . "/player.php");
$workTime = 10;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$spellList = [
	\Spells\Priest\Smite::class,
	\Spells\Priest\DC\Penance::class,
	\Spells\Priest\DC\Schism::class,
	\Spells\Priest\DC\MindBlast::class,
	\Spells\Priest\DC\PowerWordSolace::class,
	\Spells\Priest\AscendedBlast::class,
	\Spells\Priest\AscendedNova::class,
];

$db = Database::getInstance();
$id = RedisManager::getInstance()->spop(RedisManager::ROTATIONS);
if (empty($id) && $argv >= 2) {
	$id = 1800430;
}

$rotationInfo = $db->query("select * from priest_dc_work where id = {$id} limit 1 ")->fetchArray();
if (empty($rotationInfo)) {
	exit("rotation not found");
}

print_r($rotationInfo);
$rotationInfoSteps = explode(" ", $rotationInfo["rotation"]);

try {
	$rotation = new \Rotations\BaseRotation();
	$rotation->run($rotationInfoSteps);
} catch (\Exceptions\CombatTimeDone $ex) {
} finally {
	echo \Legendary\Priest\ClarityOfMind::isActive() ? "Leg ClarityOfMind apply" : "not leg";
	storeResult($id);
}


if (TimeTicker::getInstance()->getCombatTimer() >= $workTime) {
	exit("Time over");
}
$rotation->skipGcd();
addSpells($spellList, trim($rotationInfo["rotation"]));
