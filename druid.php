<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

$workTime = 60;
TimeTicker::getInstance()->getTotalWorkTime($workTime);

$rejuve = new \Spells\Druid\Restor\Rejuvenation();
$toPlayer = Place::getInstance()->getRandomNumPlayer();

Caster::playerCastSpell($toPlayer, $rejuve);
while (TimeTicker::getInstance()->tick()) {
	if (!TimeTicker::getInstance()->isGcd() && !TimeTicker::getInstance()->isCastingProgress()) {
		//Caster::playerCastSpell($toPlayer, $rejuve, false);
	}

}