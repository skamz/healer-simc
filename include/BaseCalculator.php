<?php


class BaseCalculator {

	/**
	 * @var BaseMasteryInfo
	 */
	protected $statInfo;

	protected $defaultPercent;

	public function __construct(BaseStatInfo $statInfo, int $defaultPercent) {
		$this->statInfo = $statInfo;
		$this->defaultPercent = $defaultPercent;
	}

	public function calcPercent(int $statCount, $addDefaultPercent = true) {
		$return = 0;
		$currentStat = $statCount;
		foreach ($this->statInfo->getPointPenalty() as $stepInfo) {
			if ($currentStat <= 0) {
				break;
			}
			$statsInPoint = $this->statInfo->getBasePoint() / (1 - $stepInfo["penalty"]);
			$calcStat = min($stepInfo["points"] * $statsInPoint, $currentStat);

			$return += $calcStat / $statsInPoint;
			$currentStat -= $calcStat;
		}

		if ($addDefaultPercent) {
			$return += $this->defaultPercent;
		}
		return $return;
	}

}