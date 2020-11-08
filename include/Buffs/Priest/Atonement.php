<?php


namespace Buffs\Priest;


class Atonement extends \Buff {

	protected float $duration = 15;

	public static function getHealAmount(int $damageAmount) {
		$masteryPercent = \Place::getInstance()->getMyPlayer()->getStatCalculator()->getMasteryPercent();
		$healPercent = 0.5 * (1 + $masteryPercent / 100);
		$return = $damageAmount * $healPercent;
		return $return;
	}

}