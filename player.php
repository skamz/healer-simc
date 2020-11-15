<?php

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
Place::getInstance()->getMyPlayer()
	->setConvenant(\Enum\Covenant::TYPE_VENTIR)
	->setCovenantMedium(\Enum\Medium::VENTIR_NADYA)
	->addConduit(new \Mediums\Ventir\Conduits\SwiftPenitence())
	->addConduit(new \Mediums\Ventir\Conduits\Shattered());

Place::getInstance()->addEnemy((new Enemy())->setName("Boss"));
return $player;