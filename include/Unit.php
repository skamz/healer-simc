<?php


class Unit {

	protected $mana;
	protected $health;
	protected $maxMana;
	protected $maxHealth;
	protected $buffs = [];
	protected $name;
	protected $targetNum;
	protected $targetType;

	public function addBuff(Buff $buff) {
		if (Details::$isLog) {
			echo TimeTicker::getInstance()->getCombatTimer() . " - Buff " . get_class($buff) . " <span style='background-color: green'>apply</span> to " . $this->getName() . "<br>\n";
		}
		$existNumBuff = $this->hasBuff($buff->getName());
		$buff->unit = $this;
		if (isset($existNumBuff)) {
			$this->buffs[$existNumBuff]->reApply($buff);
		} else {
			$this->buffs[] = $buff;
			$existNumBuff = array_key_last($this->buffs);
			$buff->registerTickEvent();
		}
		$this->registerFadeBuff($existNumBuff);
		return $this;
	}

	protected function registerFadeBuff($buffNum) {
		$event = new \Events\Event($this, "fadeBuff", $buffNum);
		$eventId = Events::getInstance()->registerEvent($this->buffs[$buffNum]->getIterationEnd(), $event);
		$this->buffs[$buffNum]->setFadeEventId($eventId);
	}

	public function removeBuff(string $buffName) {
		foreach ($this->buffs as $buffNum => $checkBuff) {
			if ($buffName == get_class($checkBuff)) {
				Events::getInstance()->removeEvent($this->buffs[$buffNum]->getFadeEventId());
				$this->fadeBuff($buffNum);
			}
		}
	}

	public function hasBuff(string $buffName): ?int {
		foreach ($this->buffs as $buffNum => $checkBuff) {
			if ($buffName == get_class($checkBuff)) {
				return $buffNum;
			}
		}
		return null;
	}

	public function existBuff(string $buffName): bool {
		$buffNum = $this->hasBuff($buffName);
		if (is_null($buffNum)) {
			return false;
		}
		return true;
	}


	public function getBuff(string $buffName): ?Buff {
		$buffNum = $this->hasBuff($buffName);
		if (is_null($buffNum)) {
			return null;
		}
		return $this->getBuffByNum($buffNum);
	}

	public function getBuffLostTime(int $buffName) {
		/** @var $buffObj Buff */
		$buffObj = $this->buffs[$buffName];
		return $buffObj->getDuration();
	}

	public function getBuffByNum(int $buffNum): Buff {
		return $this->buffs[$buffNum];
	}

	public function saveBuffsState(array $buffs): self {
		$this->buffs = $buffs;
		return $this;
	}

	public function getBuffs(): array {
		return $this->buffs;
	}

	public function fadeBuff(int $buffNum) {
		if (Details::$isLog) {
			echo TimeTicker::getInstance()->getCombatTimer() . " Buff " . get_class($this->buffs[$buffNum]) . " <span style='background-color: red'>fade</span> from {$this->getName()}<br>\n";
		}
		/** @var $buff Buff */
		$buff = $this->buffs[$buffNum];
		$buff->applyFade();
		unset($this->buffs[$buffNum]);
	}

	public function healTaken(int $amount, Spell $spell, Unit $unit = null) {
		if (Details::$isLog) {

			if (isset($unit)) {
				echo $unit->getName() . " ";
			}
			echo "taken heal {$amount}<br>\n";
		}
		Details::heal($amount, $spell->getName());
		$this->health += $amount;
		$this->health = min($this->health, $this->maxHealth);
		//echo "Heal unit at {$amount}<br>\n";
	}

	public function damageTaken(int $amount) {
		echo "Damage taken {$amount}<br>\n";
		$this->health -= $amount;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name) {
		$this->name = $name;
		return $this;
	}

	public function applyBuffs(string $callback, $value, Spell $fromSpell = null) {
		foreach ($this->getBuffs() as $buff) {
			$value = $buff->$callback($value, $fromSpell);
		}
		return $value;
	}

	public function setTarget(string $type, int $targetNum): self {
		$this->targetType = $type;
		$this->targetNum = $targetNum;
		return $this;
	}

	public function getTargetNum(): int {
		return $this->targetNum;
	}

}