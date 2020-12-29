<?php


class TimeTicker {

	use \Traits\Singleton;

	/**
	 * @var float
	 */
	protected $lostTime;
	protected $combatTimer;
	protected $iteration = 0;

	protected $lostGcd;
	protected $spellsCd = [];
	protected $castingTime = 0;

	const TICK_COUNT = 0.01;

	public function getTotalWorkTime(int $workTime) {
		$this->lostTime = $workTime;
	}

	public function startGcd(float $gcd) {
		$this->lostGcd = $gcd;
	}

	public function getIteration() {
		return $this->iteration;
	}

	public function tick(): bool {
		if ($this->lostTime <= 0) {
			return false;
		}
		$this->tickPets();
		$this->tickCombatTimer();
		$this->tickGcd();
		$this->tickEvents();
		//$this->tickPlayersBuffs();
		//$this->tickEnemyBuffs();
		$this->tickSpellCd();
		$this->tickSpellCasting();

		return true;
	}

	protected function tickEvents() {
		Events::getInstance()->applyEvents($this->iteration);
	}

	protected function tickPets() {
		$pets = Place::getInstance()->getPets();
		foreach ($pets as $petNum => $pet) {
			/** @var $pet \Pets\Pet */
			$pet->tick();
			if ($pet->isExpire()) {
				if ($this->combatTimer - floor($this->combatTimer) <= self::TICK_COUNT) {
					unset($pets[$petNum]);
					gc_collect_cycles();
				}

			}
		}
		Place::getInstance()->savePets($pets);
	}

	protected function tickGcd() {
		//echo "Losttime: " . $this->lostTime . "<br>\n";
		if ($this->lostGcd > 0) {
			$this->lostGcd -= self::TICK_COUNT;
		}
	}

	protected function tickCombatTimer() {
		$this->lostTime -= self::TICK_COUNT;
		$this->combatTimer += self::TICK_COUNT;
		$this->iteration++;
	}

	protected function tickPlayersBuffs() {
		$playerNums = Place::getInstance()->getPlayersAllNums();
		foreach ($playerNums as $playerNum) {
			$player = Place::getInstance()->getPlayer($playerNum);
			$player = $this->tickUnitBuffs($player);
			//Place::getInstance()->savePlayer($playerNum, $player);
		}
	}

	protected function tickUnitBuffs(Unit $player): Unit {
		$buffs = $player->getBuffs();
		foreach ($buffs as $num => $buff) {
			/** @var $buff Buff */
			$buff->tick();
			/*if ($buff->isEnd()) {
				$player->fadeBuff($num);
			} else {
				$buffs[$num] = $buff;
			}*/
		}
		return $player;
	}

	protected function tickEnemyBuffs() {
		$enemyNums = Place::getInstance()->getEnemiesAllNums();
		foreach ($enemyNums as $enemyNum) {
			$enemy = Place::getInstance()->getEnemy($enemyNum);
			$enemy = $this->tickUnitBuffs($enemy);
			//Place::getInstance()->saveEnemy($enemyNum, $enemy);
		}
	}

	protected function tickSpellCd() {
		foreach ($this->spellsCd as $spellName => $_) {
			$this->spellsCd[$spellName] -= self::TICK_COUNT;
			if ($this->spellsCd[$spellName] <= 0) {
				unset($this->spellsCd[$spellName]);
				SpellState::getInstance()->endCooldownTime($spellName);
			}
		}
	}

	protected function tickSpellCasting() {
		if (!SpellState::getInstance()->hasCastingSpell()) {
			return;
		}
		$this->castingTime -= self::TICK_COUNT;
		if ($this->castingTime <= 0) {
			SpellState::getInstance()->endCastingSpell();
		}
	}

	public function isGcd() {
		return $this->lostGcd > 0;
	}

	public function getCombatTimer() {
		return round($this->combatTimer, 2);
	}

	public function startSpellCooldown(Spell $spell) {
		$spellName = get_class($spell);
		if (isset($this->spellsCd[$spellName])) {
			throw new Exception("WTF. Использование заклинания " . get_class($spell) . " у которого не закончилось CD. Как так?");
		}
		$this->spellsCd[$spellName] = $spell->getCd();
	}

	public function getSpellCdTimer(string $spellName) {
		return $this->spellsCd[$spellName] ?: 0;
	}

	public function startCastingSpell(float $castTime) {
		$this->castingTime = $castTime;
	}

	public function cancelCasting() {
		$this->castingTime = 0;
	}

	public function isCastingProgress(): bool {
		return $this->castingTime > 0;
	}

}