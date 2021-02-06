<?php

namespace Buffs;

class RealPPM {

	use \Traits\Singleton;

	protected array $lastSinceChance;
	protected array $lastProc;

	public function tryProc($ppm, \Buff $buff) {
		$procPercent = $this->calcProcChange($ppm, $buff->getName());
		if (\Helper::isProc($procPercent)) {
			\Place::getInstance()->getMyPlayer()->addBuff($buff);
			$this->setLastProc($buff->getName());
		}
		$this->setLastChance($buff->getName());
	}

	protected function calcProcChange($ppm, string $procBuff) {
		$lastChance = $this->getLastChance($procBuff);
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();

		$seconds = min($lastChance, 3.5);
		$real_ppm = $ppm * (1 + $hastePercent / 100);
		$oldRppmChance = $real_ppm * ($seconds / 60);
		//$return = ($ppm * (1 + $hastePercent / 100) * $lastChance) / 60;
		return $oldRppmChance * 100;
	}

	protected function getLastChance(string $procBuff) {
		if (!isset($this->lastSinceChance[$procBuff])) {
			return 0;
		}
		return \TimeTicker::getInstance()->getCombatTimer() - $this->lastSinceChance[$procBuff];
	}

	protected function getLastProc(string $procBuff) {
		if (!isset($this->lastProc[$procBuff])) {
			return 10;
		}
		return \TimeTicker::getInstance()->getCombatTimer() - $this->lastProc[$procBuff];
	}

	protected function setLastChance(string $procBuff) {
		$this->lastSinceChance[$procBuff] = \TimeTicker::getInstance()->getCombatTimer();
	}

	protected function setLastProc(string $procBuff) {
		$this->lastProc[$procBuff] = \TimeTicker::getInstance()->getCombatTimer();
	}


	public function initProc(array $buffsName) {
		foreach ($buffsName as $buffName) {
			$this->lastProc[$buffName] = rand(-30, 0);
		}
	}
}