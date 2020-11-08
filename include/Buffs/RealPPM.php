<?php

namespace Buffs;

class RealPPM {

	use \Traits\Singleton;

	protected array $lastSinceChance;

	public function tryProc($ppm, \Buff $buff) {
		$procPercent = $this->calcProcChange($ppm, $buff->getName());
		if (\Helper::isProc($procPercent)) {
			\Place::getInstance()->getMyPlayer()->addBuff($buff);
		}
		$this->setLastChance($buff->getName());
	}

	protected function calcProcChange($ppm, string $procBuff) {
		$lastChance = $this->getLastChance($procBuff);
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();
		$return = ($ppm * (1 + $hastePercent / 100) * $lastChance) / 60;
		return $return * 100;
	}

	protected function getLastChance(string $procBuff) {
		if (!isset($this->lastSinceChance[$procBuff])) {
			return 10;
		}
		return \TimeTicker::getInstance()->getCombatTimer() - $this->lastSinceChance[$procBuff];
	}

	protected function setLastChance(string $procBuff) {
		$this->lastSinceChance[$procBuff] = \TimeTicker::getInstance()->getCombatTimer();
	}
}