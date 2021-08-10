<?php
const INC_AMOUNT = 50;

$statCalculator = CalculatorFactory::createStatCalculator(\Enum\SpecList::PRIEST_DC);
$player = Player::getInstance()
	->setInt(1861)
	->setCrit(351)
	->setHaste(988)
	->setMatery(507)
	->setVersatility(249)
	->setName("Player-Main")
	->setStatCalculator($statCalculator);

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
	->setConvenant(\Enum\Covenant::TYPE_VENTIR)
	->setCovenantMedium(\Enum\Medium::VENTIR_NADYA)
	//->addConduit(new \Mediums\Ventir\Conduits\Shattered());
	->addConduit(new \Mediums\Ventir\Conduits\RabidShadows());

\Buffs\RealPPM::getInstance()->initProc([
	\Buffs\CelestialGuidance::class,
	\Buffs\GladiatorInsignia::class,
	\Buffs\Priest\PowerDarkSide::class,
]);

Place::getInstance()->addEnemy((new Enemy())->setName("Boss"));
return $player;