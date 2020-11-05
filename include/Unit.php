<?php


class Unit {

	protected $mana;
	protected $health;
	protected $maxMana;
	protected $maxHealth;
	protected $buffs = [];
	protected $debufs = [];
	protected $name;

	public function addBuff(Buff $buff) {
		echo TimeTicker::getInstance()->getCombatTimer() . " - Buff " . get_class($buff) . " <span style='background-color: green'>apply</span> to " . $this->getName() . "<br>\n";
		$existNumBuff = $this->hasBuff($buff);
		if ($existNumBuff) {
			$this->buffs[$existNumBuff]->reApply($buff);
		} else {
			$this->buffs[] = $buff;
		}
		return $this;
	}

	public function hasBuff(Buff $buff): ?int {
		$searchClass = get_class($buff);
		foreach ($this->buffs as $buffNum => $checkBuff) {
			if ($searchClass == get_class($checkBuff)) {
				return $buffNum;
			}
		}
		return null;
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
		/** @var $buff Buff */
		$buff = $this->buffs[$buffNum];
		$buff->applyFade();
		unset($this->buffs[$buffNum]);
	}

	public function healTaken(int $amount) {
		echo TimeTicker::getInstance()->getCombatTimer() . " - " . $this->getName() . " taken heal {$amount}<br>\n";
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

}