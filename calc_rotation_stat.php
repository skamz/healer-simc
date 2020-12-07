<?php

require_once(__DIR__ . "/autoloader.php");

function findTotalOut(array $out) {
	foreach ($out as $str) {
		if (preg_match("!total heal: (?<heal>[0-9]+)!", $str, $match)) {
			return $match["heal"];
		}
	}
	return false;
}

function getCommandResult($cmd) {
	$out = [];
	exec($cmd, $out);
	return findTotalOut($out);
}

function getAverageHeal($cmd, $iterationCount) {
	$steps = [];

	$prev = false;
	$totalIterations = 0;
	$iteration = -5000;
	do {
		$totalIterations++;
		$healAmount = getCommandResult($cmd);
		if ($healAmount !== false) {
			$steps[] = $healAmount;
		}
		if ($iteration++ > 500) {
			if ($prev === false) {
				$prev = round(array_sum($steps) / count($steps));
				$iteration = 0;
				continue;
			} else {
				$current = round(array_sum($steps) / count($steps));
				$diff = abs($current - $prev);
				echo "$current ({$diff})   \r";
				if ($diff > 500) {
					$prev = $current;
					$iteration = 0;
					continue;
				} else {
					break;
				}
			}
		} else {
			if ($iteration < 0) {
				echo abs(round($iteration / 1000 * 100)) . "%     \r";
			}
		}
	} while (true);
	echo "Total done iterations: {$totalIterations}\n";
	return array_sum($steps) / count($steps);
}

include(__DIR__ . "/player.php");

$phpPath = "php";
$script = __DIR__ . "/rotation_checker_stat.php";

$command = "{$phpPath} {$script}";
$emptyIteration = 0;

global $globalRotation;
$checkRotation = $globalRotation;
echo "test rotation: " . $checkRotation . "\n";

$baseCmd = "$command --rotation='{$checkRotation}'";


$incCount = 50;
$iterationCount = 500;


echo "Base rotation\n";
$avgBase = getAverageHeal($baseCmd, $iterationCount);
echo "\nBase rotation amount {$avgBase}\n";

//Int
$cmd = "{$baseCmd} --int=" . (Place::getInstance()->getMyPlayer()->getInt() + $incCount);
echo "Int rotation: {$cmd}\n";
$avgInt = getAverageHeal($cmd, $iterationCount);
echo "\nInt rotation amount {$avgInt}\n";

//CRIT
$cmd = "{$baseCmd} --crit=" . (Place::getInstance()->getMyPlayer()->getCritCount() + $incCount);
echo "Crit rotation: {$cmd}\n";
$avgCrit = getAverageHeal($cmd, $iterationCount);
echo "\nCrit rotation amount {$avgCrit}\n";

//haste
$cmd = "{$baseCmd} --haste=" . (Place::getInstance()->getMyPlayer()->getHasteCount() + $incCount);
echo "Haste rotation: {$cmd}\n";
$avgHaste = getAverageHeal($cmd, $iterationCount);
echo "\nHaste rotation amount {$avgHaste}\n";

//mastery
$cmd = "{$baseCmd} --mastery=" . (Place::getInstance()->getMyPlayer()->getMasteryCount() + $incCount);
echo "Mastery rotation: {$cmd}\n";
$avgMastery = getAverageHeal($cmd, $iterationCount);
echo "\nMastery rotation amount {$avgMastery}\n";

//versa
$cmd = "{$baseCmd} --versa=" . (Place::getInstance()->getMyPlayer()->getVersatilityCount() + $incCount);
echo "Versa rotation: {$cmd}\n";
$avgVersa = getAverageHeal($cmd, $iterationCount);
echo "\nVersa rotation amount {$avgVersa}\n";


$stats = [];
$stats["int"] = max(0, round(($avgInt - $avgBase) / $incCount, 2));
$stats["crit"] = max(0, round(($avgCrit - $avgBase) / $incCount, 2));
$stats["haste"] = max(0, round(($avgHaste - $avgBase) / $incCount, 2));
$stats["mastery"] = max(0, round(($avgMastery - $avgBase) / $incCount, 2));
$stats["versa"] = max(0, round(($avgVersa - $avgBase) / $incCount, 2));

$normalize = max($stats);
print_r($stats);

arsort($stats);
echo "=======================\n";
foreach ($stats as $name => $value) {
	echo "{$name}: " . round($value / $normalize, 2) . "\n";
}