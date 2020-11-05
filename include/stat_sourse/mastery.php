<?php

require_once(dirname(__DIR__, 2) . "/autoloader.php");

$spec = $_GET["spec"];

if (empty($spec)) {
	throw new Exception("GET spec не указан");
}

$masteryInfo = require_once(__DIR__ . "/specs/{$spec}/mastery_penalty.php");

$masteryCalc = new MasteryCalculator($masteryInfo);

$statCount = $_GET["count"];
echo "{$statCount} = " . $masteryCalc->calcPercent($statCount);