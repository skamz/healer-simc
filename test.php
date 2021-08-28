<?php

require_once(__DIR__ . "/autoloader.php");


$player = include(__DIR__ . "/player.php");

for ($i = 0; $i < 400; $i++) {
	Database::getInstance()->query("delete from `priest_dc_work_all` WHERE `iterations`=1 limit 10000");
}