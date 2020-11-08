<?php


class SpellState {
	const CAST_TO_PLAYER = "player";
	const CAST_TO_ENEMY = "enemy";

	use \Traits\Singleton;

	/* @var $spells Spell[] */
	protected array $spells = [];

	protected array $casting;

	public function registerSpellByName(string $spellName) {
		if (isset($this->spells[$spellName])) {
			return $this;
		}
		return $this->registerSpell(new $spellName());
	}

	public function registerSpell(Spell $spell) {
		$name = get_class($spell);
		$this->spells[$name] = $spell;
		return $this;
	}

	public function cast(string $spellName) {
		$this->registerSpellByName($spellName);
		$this->spells[$spellName]->decChangeCount();
		$this->checkStartCooddownTimer($spellName);
	}

	public function endCooldownTime(string $spellName) {
		$this->spells[$spellName]->incChangeCount();
		$this->checkStartCooddownTimer($spellName);
	}

	public function getChangeCount($spellName) {
		return $this->spells[$spellName]->getChangeCount();
	}

	protected function checkStartCooddownTimer(string $spellName) {
		$this->registerSpellByName($spellName);
		$lostTimer = TimeTicker::getInstance()->getSpellCdTimer($spellName);
		if (empty($lostTimer) && $this->spells[$spellName]->getChangeCount() < $this->spells[$spellName]->getMaxChangeCount()) {
			TimeTicker::getInstance()->startSpellCooldown($this->spells[$spellName]);
		}
	}

	public function isAvailable(string $spellName): bool {
		$this->registerSpellByName($spellName);
		if (!isset($this->spells[$spellName])) {
			return false;
		}
		if ($this->spells[$spellName]->getChangeCount() == 0) {
			return false;
		}

		return true;
	}

	public function startCastingSpell(string $castToType, int $castToNum, Spell $spell) {
		echo TimeTicker::getInstance()->getCombatTimer() . ": Start cast " . $spell->getName() . "<br>\n";
		$this->casting = [
			"cast_type" => $castToType,
			"cast_to_num" => $castToNum,
			"spell" => $spell,
		];
		TimeTicker::getInstance()->startCastingSpell($spell->getCastTime());
	}

	public function cancelCasting() {
		unset($this->casting);
		TimeTicker::getInstance()->cancelCasting();
	}

	public function endCastingSpell() {
		if (empty($this->casting)) {
			throw new Exception("endCastingSpell но нет информации о текущем заклинании");
		}
		switch ($this->casting["cast_type"]) {
			case self::CAST_TO_ENEMY:
				Caster::applySpellToEnemy($this->casting["cast_to_num"], $this->casting["spell"]);
				break;

			case self::CAST_TO_PLAYER:
				Caster::applySpellToPlayer($this->casting["cast_to_num"], $this->casting["spell"]);
				break;
		}
		unset($this->casting);
	}


	public function hasCastingSpell(): bool {
		if (empty($this->casting)) {
			return false;
		}
		return true;
	}

}