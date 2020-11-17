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

}