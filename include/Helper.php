<?php


class Helper {

	public static function isProc($chance, $max = 100, $precision = 100) {
		$rnd = rand(0, $max * $precision) / $precision;
		if ($chance >= $rnd) {
			return true;
		}
		return false;
	}

}