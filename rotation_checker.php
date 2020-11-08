<?php

require_once(__DIR__ . "/autoloader.php");

$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();

TimeTicker::getInstance()->getTotalWorkTime(30);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();

$db = new Database();
$rotationInfo = $db->query("select * from priest_dc where iterations < 5 order by rand() limit 1")->fetchArray();
if (empty($rotationInfo)) {
	exit;
}
$rotationInfoSteps = explode(" ", $rotationInfo["rotation"]);
$rotationVariables = [];

//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		if (!empty($rotationInfoSteps)) {
			$nextSpell = current($rotationInfoSteps);
		} else {
			break;
		}

		$toPlayer = Place::getInstance()->getRandomNumPlayerWithBuff(\Buffs\Priest\Atonement::class);
		if (\Spells\Priest\DC\Schism::isAvailable() && $nextSpell == "Schism") {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\Penance::isAvailable() && $nextSpell == "Penance") {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\Smite::isAvailable() && $nextSpell == "Smite") {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable() && $nextSpell == "Radiance") {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\PowerWordShield::isAvailable() && $nextSpell == "Shield") {
			Caster::castSpellToPlayer($toPlayer, $shield);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\Mindgames::isAvailable() && $nextSpell == "Mindgames") {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Mindgames());
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\PowerWordSolace::isAvailable() && $nextSpell == "Solace") {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			array_shift($rotationInfoSteps);
		}

	}

}


if (\Spells\Priest\DC\Schism::isAvailable()) {
	$rotationVariables[] = "Schism";
}
if (\Spells\Priest\DC\Penance::isAvailable()) {
	$rotationVariables[] = "Penance";
}
if (\Spells\Priest\Smite::isAvailable()) {
	$rotationVariables[] = "Smite";
}
if (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
	$rotationVariables[] = "Radiance";
}
if (\Spells\Priest\PowerWordShield::isAvailable()) {
	$rotationVariables[] = "Shield";
}
if (\Spells\Priest\DC\Mindgames::isAvailable()) {
	$rotationVariables[] = "Mindgames";
}
if (\Spells\Priest\DC\PowerWordSolace::isAvailable()) {
	$rotationVariables[] = "Solace";
}


$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";

$db->query("update priest_dc set total_heal = total_heal + {$totalResult}, iterations = iterations + 1, avg_heal = total_heal / iterations where id ={$rotationInfo["id"]}");

print_r($rotationVariables);
foreach ($rotationVariables as $nextSpell) {
	$testRotation = "{$rotationInfo["rotation"]} {$nextSpell}";
	$row = $db->query("select 1 from priest_dc where rotation='{$testRotation}' limit 1")->fetchArray();
	if (empty($row)) {
		$db->query("insert into priest_dc set rotation='{$testRotation}'");
	}
}