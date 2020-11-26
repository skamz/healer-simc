<?php


class Details {

	protected static array $heal = [];
	protected static array $damage = [];

	public static bool $isLog = true;

	public static function damage(int $amount, string $spellName) {
		if (!self::$isLog) return;
		echo "Damage enemy by {$amount}<br>\n";
		$base = basename($spellName);
		self::$damage[$base] += $amount;
	}

	public static function heal(int $amount, string $spellName) {
		if (!self::$isLog) return;
		$iteration = TimeTicker::getInstance()->getIteration();
		$base = basename($spellName);
		if (!isset(self::$heal[$iteration])) {
			self::$heal[$iteration] = [];
		}
		self::$heal[$iteration][$base] += $amount;
	}

	protected static function getTotalHeal() {
		$return = [];
		foreach (self::$heal as $iteration => $info) {
			foreach ($info as $spell => $amount) {
				$return[$spell] += $amount;
			}
		}
		return $return;
	}

	public static function getSummaryHeal() {
		$total = self::getTotalHeal();
		return array_sum($total);
	}

	public static function getHealBySeconds() {
		$secondsHeal = [];
		foreach (self::$heal as $iteration => $info) {
			$second = floor($iteration * TimeTicker::TICK_COUNT);
			$secondsHeal[$second] += array_sum($info);
		}
		return $secondsHeal;
	}

	public static function getMedianByPeriod($period = 10) {
		$secondsHeal = self::getHealBySeconds();
		if (empty($secondsHeal)) {
			return 0;
		}

		$startPeriods = [];
		$maxSecond = max(array_keys($secondsHeal));
		for ($i = 0; $i <= max(0, $maxSecond - $period); $i++) {
			$periodSeconds = self::getSecondsSlice($secondsHeal, $i, $i + $period);
			$periodMedian = Helper::getMedian($periodSeconds);
			foreach ($periodSeconds as $healAmount) {
				$startPeriods[$i] += min($healAmount, $periodMedian);
			}
			$startPeriods[$i] = intval($startPeriods[$i] / $period);
		}

		return max($startPeriods);
	}

	public static function printTimeHeal() {
		if (!self::$isLog) return;
		$pushData = [];
		foreach (self::$heal as $iteration => $info) {
			$iterationAmount = 0;
			foreach ($info as $spell => $amount) {
				$iterationAmount += $amount;
			}
			$pushData[] = " arr.push([" . round($iteration * TimeTicker::TICK_COUNT, 2) . ", {$iterationAmount}]);";
		}
		echo '<div id="time_heal_id" style="width: 900px; height: 500px"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawTimeHeal);

function drawTimeHeal() {
  var arr = [["Itaretion", "Amount"]];
  ';
		echo implode("\n", $pushData);

		echo '
  var data = google.visualization.arrayToDataTable(arr);

  var options = {
    title: "Approximating Normal Distribution",
    legend: { position: "none" }
  };

  var chart = new google.visualization.ColumnChart(document.getElementById("time_heal_id"));

  chart.draw(data, options);
}
</script>';
	}

	public static function printHeal() {
		if (!self::$isLog) return;
		self::printTimeHeal();
		$total = self::getTotalHeal();
		arsort($total);

		echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<div id="heal_chart_div" style="position: fixed;right: 30px;top:500px;width: 1200px;"></div>';

		$dataRows = [];
		foreach ($total as $name => $value) {
			$dataRows[] = "['{$name}', {$value}]";
		}

		echo '<script>
google.charts.load("current", {packages: ["corechart", "bar"]});
google.charts.setOnLoadCallback(drawHeal);

function drawHeal() {
      var data = google.visualization.arrayToDataTable([
        ["Speel", "Damage"],
        ' . implode(", ", $dataRows) . '
      ]);

      var options = {
        title: "ВСЕГО исцеления",
        chartArea: {width: "50%"},
        hAxis: {
          title: "Total Population",
          minValue: 0
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById("heal_chart_div"));

      chart.draw(data, options);
    }
</script>';
	}

	public static function printDamage() {
		if (!self::$isLog) return;
		arsort(self::$damage);
		echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<div id="damage_chart_div" style="position: fixed;right: 30px;top:30px;width: 1200px;"></div>';

		$dataRows = [];
		foreach (self::$damage as $name => $value) {
			$dataRows[] = "['{$name}', {$value}]";
		}

		echo '<script>
google.charts.load("current", {packages: ["corechart", "bar"]});
google.charts.setOnLoadCallback(drawDamage);

function drawDamage() {
      var data = google.visualization.arrayToDataTable([
        ["Speel", "Damage"],
        ' . implode(", ", $dataRows) . '
      ]);

      var options = {
        title: "Нанесенный урон",
        chartArea: {width: "50%"},
        hAxis: {
          title: "Total damage",
          minValue: 0
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById("damage_chart_div"));

      chart.draw(data, options);
    }
</script>';
	}

	public static function analyzePeakByMedian() {
		$secondsHeal = self::getHealBySeconds();
		if (empty($secondsHeal)) {
			return true;
		}
		$isFindPeakSecond = false;
		$maxSecond = max(array_keys($secondsHeal));
		for ($i = 3; $i < $maxSecond; $i++) {
			$slice = self::getSecondsSlice($secondsHeal, 0, $i);
			$sliceMedian = Helper::getMedian($slice);
			if (empty($sliceMedian)) {
				echo "Bad branch, start fail<br>\n";
				return false;
			}
			if ($secondsHeal[$i + 1] > $sliceMedian * 4) {
				$isFindPeakSecond = $i + 1;
				echo ($i + 1) . " second is PEAK<br>\n";
				if (self::analyzeAfterPeak($secondsHeal, $i + 1) === false) {
					echo "Bad branch, analyzeAfterPeak<br>\n";
					return false;
				}
			}
		}
		if ($maxSecond >= 10) {
			if ($isFindPeakSecond === false) {
				echo "Bad branch, no peak<br>\n";
				return false;
			}
			/*$maxPeriodHeal = self::getMedianByPeriod(4);
			$avgResult = self::getAvgResult();
			if ($maxPeriodHeal > 0 and $avgResult > $maxPeriodHeal) {
				echo "Avg more what 10 seconds<br>\n";
				return false;
			}*/
		}
		return true;
	}

	public static function getAvgResult() {
		$avgCache = RedisManager::getInstance()->get(RedisManager::AVG_RESULT);
		if (empty($avgCache)) {
			$db = Database::getInstance();
			$avgInfo = $db->query("select avg(avg_heal) as aheal, count(*) as cnt from priest_dc_result where iterations > 0")->fetchArray();
			if ($avgInfo["cnt"] < 500) {
				return false;
			}
			$avgCache = $avgInfo["aheal"];
			RedisManager::getInstance()->set(RedisManager::AVG_RESULT, $avgCache);
		}
		return $avgCache;

	}


	protected static function getSecondsSlice(array $secondsHeal, int $from, int $to): array {
		$return = [];
		for ($i = $from; $i < $to; $i++) {
			$return[$i] = $secondsHeal[$i] ?? 0;
		}
		return $return;
	}

	protected static function analyzeAfterPeak(array $secondsHeal, $peakSecond) {
		$maxSecond = max(array_keys($secondsHeal));
		if ($peakSecond + 4 > $maxSecond) {
			return true;
		}
		$sliceBeforePeak = self::getSecondsSlice($secondsHeal, 0, $peakSecond - 1);
		$sliceAfterPeak = self::getSecondsSlice($secondsHeal, $peakSecond + 1, $peakSecond + 5);

		$medianAfterPeak = Helper::getMedian($sliceAfterPeak);
		$medianBeforePeak = Helper::getMedian($sliceBeforePeak);
		if ($medianAfterPeak < $medianBeforePeak * 2) {
			return false;
		}
		return true;
	}

}