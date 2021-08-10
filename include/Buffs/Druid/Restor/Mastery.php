<?php


namespace Buffs\Druid\Restor;


class Mastery extends \Buff {

	public function hots() {
		return [
			Rejuvenation::class,
		];
	}

	public function getHotCount(\Player $target) {
		$hots = $this->hots();
		$hotsMap = array_combine($hots, array_fill(0, count($hots), true));
		$return = 0;
		/** @var \Buff $buff */
		foreach ($target->getBuffs() as $buff) {
			if (!empty($hotsMap[$buff->getName()])) {
				$return++;
			}
		}
		return $return;
	}

	public function getPercent(\Player $target) {
		return \Player::getInstance()->getStatCalculator()->getMasteryPercent() * $this->getHotCount($target);
	}

	public function applyOnHeal(int $healCount, \Spell $fromSpell = null, \Player $target = null) {
		$incPercent = $this->getPercent($target);
		return $healCount * (1 + $incPercent / 100);
	}

}