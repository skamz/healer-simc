<?php
require_once(dirname(__DIR__, 2) . "/autoloader.php");

$spec = $_GET["spec"];

if (empty($spec)) {
	throw new Exception("GET spec не указан");
}

$statInfo = new BaseVersatilityInfo();
$statCalc = new VersatilityCalculator($statInfo);

$statCount = $_GET["count"];
echo "{$statCount} = " . $statCalc->calcPercent($statCount);