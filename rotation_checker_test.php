<?php
xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

require_once(__DIR__ . "/autoloader.php");

const PENANCE = 1;
const SCHISM = 2;
const SMITE = 3;
const RADIANCE = 4;
const SHIELD = 5;
const MINDGAMES = 6;
const SOLACE = 7;


$player = include(__DIR__ . "/player.php");
$damageEnemy = Place::getInstance()->getRandomEnemy();

$workTime = 30;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();
$solace = new \Spells\Priest\DC\PowerWordSolace();

//$db = new Database();
//$countInfo = $db->query("select count(*) as cnt from priest_dc")->fetchArray();
//$id = rand(1, $countInfo["cnt"]);
//$id = RedisManager::getInstance()->spop(RedisManager::ROTATIONS);

/*$rotationInfo = $db->query("select * from priest_dc_work where iterations = 0 and id = {$id} limit 1 ")->fetchArray();
if (empty($rotationInfo)) {
	exit;
}
$rotationInfoSteps = explode(" ", $rotationInfo["rotation"]);*/
$rotationInfoSteps = explode(" ", "5 5 5 4 4 1 6 3 7 5 3 5 5 5 5 3 5 2 3 5 1");
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
		if (\Spells\Priest\DC\Schism::isAvailable() && ($nextSpell == "Schism" || $nextSpell == SCHISM)) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\Penance::isAvailable() && ($nextSpell == "Penance" || $nextSpell == PENANCE)) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\Smite::isAvailable() && ($nextSpell == "Smite" || $nextSpell == SMITE)) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable() && ($nextSpell == "Radiance" || $nextSpell == RADIANCE)) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\PowerWordShield::isAvailable() && ($nextSpell == "Shield" || $nextSpell == SHIELD)) {
			Caster::castSpellToPlayer($toPlayer, $shield);
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\Mindgames::isAvailable() && ($nextSpell == "Mindgames" || $nextSpell == MINDGAMES)) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Mindgames());
			array_shift($rotationInfoSteps);
		} elseif (\Spells\Priest\DC\PowerWordSolace::isAvailable() && ($nextSpell == "Solace" || $nextSpell == SOLACE)) {
			Caster::castSpellToEnemy($damageEnemy, $solace);
			array_shift($rotationInfoSteps);
		}

	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";

$saveToDir = "F:/OpenServer/docker/xhprof-php-docker-viewer-master/trases/xhprof/" . time() . ".wowsim.xhprof";
$xhprof_data = serialize(xhprof_disable());
file_put_contents($saveToDir, $xhprof_data);


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
if (\Spells\Priest\Smite::isAvailable()) {
	$rotationVariables[] = SMITE;
}
if (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
	$rotationVariables[] = RADIANCE;
}
if (\Spells\Priest\PowerWordShield::isAvailable()) {
	$rotationVariables[] = SHIELD;
}
if (\Spells\Priest\DC\Mindgames::isAvailable()) {
	$rotationVariables[] = MINDGAMES;
}
if (\Spells\Priest\DC\PowerWordSolace::isAvailable()) {
	$rotationVariables[] = SOLACE;
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