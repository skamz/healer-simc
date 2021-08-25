<?php
const INC_AMOUNT = 50;

$statCalculator = CalculatorFactory::createStatCalculator(\Enum\SpecList::PRIEST_DC);
$player = (new Player())
	->setInt(1827)
	->setCrit(657)
	->setHaste(930)
	->setMatery(207)
	->setVersatility(365)
	->setName("Player-Main")
	->setStatCalculator($statCalculator);
Place::getInstance()->addPlayer($player);

global $globalRotation;
$options = getopt("r:i:c:h:m:v", ["rotation:", "int:", "crit:", "haste:", "mastery:", "versa:"]);
foreach ($options as $name => $value) {
	switch ($name) {
		case "int":
			$player->setInt($value);
			break;

		case "crit":
			$player->setCrit($value);
			break;

		case "haste":
			$player->setHaste($value);
			break;

		case "mastery":
			$player->setMatery($value);
			break;

		case "versa":
			$player->setVersatility($value);
			break;

		case "rotation":
			$globalRotation = $value;
			break;
	}
}

Place::getInstance()->savePlayer(Place::ANALYZE_PLAYER, $player);

for ($i = 1; $i < 20; $i++) {
	$addPlayer = clone $player;
	$addPlayer = $addPlayer->setName("Player{$i}");
	Place::getInstance()->addPlayer($addPlayer);
}
Place::getInstance()->getMyPlayer()
	->setLegendary(\Legendary\Priest\ClarityOfMind::class)
	->setConvenant(\Enum\Covenant::TYPE_KYRIAN)
	->setCovenantMedium(\Mediums\Kyrian\Mikanikos::class)
	->addConduit(new \Mediums\Ventir\Conduits\RabidShadows());

\Buffs\RealPPM::getInstance()->initProc([
	\Buffs\CelestialGuidance::class,
	\Buffs\GladiatorInsignia::class,
	\Buffs\Priest\PowerDarkSide::class,
]);

Place::getInstance()->addEnemy((new Enemy())->setName("Boss"));
return $player;