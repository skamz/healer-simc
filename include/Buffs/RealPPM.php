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
		return $oldRppmChance * 100;
		//return $this->applyBLP($procBuff, $ppm, $oldRppmChance) * 100;
	}

	protected function applyBLP(string $procBuff, $ppm, $oldRppmChance) {
		/*
		double last_success = std::min( accumulated_bad_luck_protection, max_bad_luck_prot() ).total_seconds();
		double expected_average_proc_interval = 60.0 / real_ppm;

		rppm_chance =
			std::max( 1.0, 1 + ( ( last_success / expected_average_proc_interval - 1.5 ) * 3.0 ) ) * old_rppm_chance;
		*/
		$lastSuccess = $this->getLastProc($procBuff);
		$expectedAverageProcInterval = 60 / $ppm;
		$rppmChance = max(1, 1 + (($lastSuccess / $expectedAverageProcInterval - 1.5) * 3)) * $oldRppmChance;
		return $rppmChance;
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