<?php
require_once(dirname(__DIR__, 2) . "/autoloader.php");

$spec = $_GET["spec"];

if (empty($spec)) {
	throw new Exception("GET spec не указан");
}

$critInfo = new BaseCritInfo();
$masteryCalc = new CritCalculator($critInfo);

$statCount = $_GET["count"];
echo "{$statCount} = " . $masteryCalc->calcPercent($statCount);