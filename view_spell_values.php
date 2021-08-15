<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

$workTime = 40;
TimeTicker::getInstance()->getTotalWorkTime($workTime);


echo "Smite (Кара): " . (new \Spells\Priest\Smite())->getDamageAmount() . "<br>";
echo "Penance (Исповедь) damage: " . (new \Spells\Priest\DC\Penance())->getDamageAmount() . "<br>";
echo "MindBlast (Взрыв разума): " . (new \Spells\Priest\DC\MindBlast())->getDamageAmount() . "<br>";
echo "PowerWordSolace (Слово силы: утешение): " . (new \Spells\Priest\DC\PowerWordSolace())->getDamageAmount() . "<br>";
echo "Schism (Схизма): " . (new \Spells\Priest\DC\Schism())->getDamageAmount() . "<br>";
echo "AscendedBlast (Взрыв перерождения): " . (new \Spells\Priest\AscendedBlast())->getDamageAmount() . "<br>";
echo "AscendedNova (Кольцо перерождения): " . (new \Spells\Priest\AscendedNova())->getDamageAmount() . "<br>";
echo "PurgeWicked (Очищение зла): " . (new \Spells\Priest\DC\PurgeWicked())->getDamageAmount() . "<br>";
Caster::applySpellToEnemy(Place::getInstance()->getRandomEnemy(), new \Spells\Priest\DC\PurgeWicked());


echo "<hr>";
echo "Penance (Исповедь) heal: " . (new \Spells\Priest\DC\Penance())->getHealAmount() . "<br>";

while (TimeTicker::getInstance()->tick()) {}

Details::printDamage();