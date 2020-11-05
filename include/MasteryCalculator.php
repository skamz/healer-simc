<?php


class MasteryCalculator extends BaseCalculator {

	public function calcPercent(int $statCount, $_ = false) {
		$return = parent::calcPercent($statCount, false);
		return $this->defaultPercent + round($return * $this->statInfo->getPointPercents(), 2);
	}
}