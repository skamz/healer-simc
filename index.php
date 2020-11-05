<?php

require_once(__DIR__ . "/autoloader.php");

//include (__DIR__."/include/Generators/priest_dc.php");exit;

$statCalculator = CalculatorFactory::createStatCalculator(\Enum\SpecList::PRIEST_DC);
$player = Player::getInstance()
	->setInt(977)
	->setCrit(217)
	->setHaste(256)
	->setMatery(263)
	->setVersatility(98)
	->setName("Player-Main")
	->setStatCalculator($statCalculator);


Place::getInstance()->savePlayer(Place::ANALYZE_PLAYER, $player);
for ($i = 1; $i < 20; $i++) {
	$addPlayer = clone $player;
	$addPlayer = $addPlayer->setName("Player{$i}");
	Place::getInstance()->addPlayer($addPlayer);
}

Place::getInstance()->addEnemy((new Enemy())->setName("Boss"));
$damageEnemy = Place::getInstance()->getRandomEnemy();

TimeTicker::getInstance()->getTotalWorkTime(20);

echo "<hr>";

$shield = new  \Spells\Priest\PowerWordShield();
$penance = new \Spells\Priest\DC\Penance();
$smite = new \Spells\Priest\Smite();
$radiance = new \Spells\Priest\DC\PowerWordRadiance();


//$workTime = 300 * 10000;
$time = 0;
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		$nextSpell = current($rotation);
		$toPlayer = Place::getInstance()->getRandomNumPlayer();
		if (\Spells\Priest\DC\Schism::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, new \Spells\Priest\DC\Schism());
			array_shift($rotation);
		} elseif (\Spells\Priest\DC\Penance::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $penance);
		} elseif (\Spells\Priest\Smite::isAvailable()) {
			Caster::castSpellToEnemy($damageEnemy, $smite);
		} elseif (\Spells\Priest\DC\PowerWordRadiance::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $radiance);
		} elseif (\Spells\Priest\PowerWordShield::isAvailable()) {
			Caster::castSpellToPlayer($toPlayer, $shield);
		}
	}

}

$totalResult = intval(Place::getTotalHeal());
echo "total heal: " . $totalResult . "<br>\n";

$db->query("update priest_dc set total_heal=total_heal+{$totalResult}, iterations=iterations + 1, avg_heal=total_heal/iterations where id={$rotationInfo["id"]}");