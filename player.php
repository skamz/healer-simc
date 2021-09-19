<?php


$statCalculator = CalculatorFactory::createStatCalculator(\Enum\SpecList::PALADIN_HOLY);
$player = (new Player())
	->setInt(1856)
	->setCrit(367)
	->setHaste(1138)
	->setMatery(383)
	->setVersatility(252)
	->setName("Player-Main")
	->setStatCalculator($statCalculator);
Place::getInstance()->addPlayer($player);

define("INC_AMOUNT", intval($player->getInt() * 0.05));

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
Player::getInstance()
	//->setLegendary(\Legendary\Priest\ClarityOfMind::class)
	->registerResource(\Resources\EnumList::PALADIN_HOLY_POWER, new \Resources\HolyPower())
	->setConvenant(\Enum\Covenant::TYPE_VENTIR)
	->setCovenantMedium(\Mediums\Ventir\Theotar::class);
//->addConduit(new \Mediums\Ventir\Conduits\RabidShadows());

\Buffs\RealPPM::getInstance()->initProc([
	\Buffs\CelestialGuidance::class,
	// add DivinePurpose
]);

Place::getInstance()->addEnemy((new Enemy())->setName("Boss"));
return $player;