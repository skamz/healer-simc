<?php

require_once(__DIR__ . "/autoloader.php");
$player = include(__DIR__ . "/player.php");

echo "crit: ".Player::getInstance()->getStatCalculator()->getCritPercent()."<br>";
echo "hast: ".Player::getInstance()->getStatCalculator()->getHastePercent()."<br>";
echo "mastery: ".Player::getInstance()->getStatCalculator()->getMasteryPercent()."<br>";
echo "vers: ".Player::getInstance()->getStatCalculator()->getVersatilityPercent()."<br>";