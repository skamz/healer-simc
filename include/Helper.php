<?php


class Helper {

	public static function isProc($chance, $max = 100, $precision = 100) {
		$rnd = rand(0, $max * $precision) / $precision;
		if ($chance >= $rnd) {
			return true;
		}
		return false;
	}

	public static function getMedian(array $arr) {
		if (empty($arr)) {
			return false;
		}
		sort($arr);
		$num = count($arr);
		$middleVal = floor(($num - 1) / 2);
		if ($num % 2) {
			return $arr[$middleVal];
		} else {
			$lowMid = $arr[$middleVal];
			$highMid = $arr[$middleVal + 1];
			return (($lowMid + $highMid) / 2);
		}
	}

}