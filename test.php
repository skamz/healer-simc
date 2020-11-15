<?php

require_once(__DIR__ . "/autoloader.php");


$player = include(__DIR__ . "/player.php");

$shield = new  \Spells\Priest\PowerWordShield();
Caster::castSpellToPlayer(Place::ANALYZE_PLAYER, $shield);