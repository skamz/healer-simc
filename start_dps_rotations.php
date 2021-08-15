<?php

require_once(__DIR__ . "/autoloader.php");

RedisManager::getInstance()->sadd(RedisManager::FUTURE_ROTATION, \Rotations\Priest\DCSpells::BOON_OF_ASCENDED);