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
		$this->getSpellObject($spellName)->decChangeCount();
		$this->checkStartCooldownTimer($spellName);
	}

	public function endCooldownTime(string $spellName) {
		$this->getSpellObject($spellName)->incChangeCount();
		$this->checkStartCooldownTimer($spellName);
	}

	protected function getSpellObject(string $spellName): Spell {
		if (empty($this->spells[$spellName])) {
			$this->registerSpellByName($spellName);
		}
		return $this->spells[$spellName];
	}

	public function getChangeCount($spellName) {
		return $this->getSpellObject($spellName)->getChangeCount();
	}

	protected function checkStartCooldownTimer(string $spellName) {
		$spell = $this->getSpellObject($spellName);
		if (!$spell->getIsChangeCooldown() && $spell->getChangeCount() < $spell->getMaxChangeCount()) {
			Details::log("Start {$spellName} cooldown {$spell->getCd()} sec");
			$event = new \Events\Event($this, "endCooldownTime", $spellName);
			$iterationStep = TimeTicker::getInstance()->getIterationAfterTime($spell->getCd());
			Events::getInstance()->registerEvent($iterationStep, $event);
			$spell->setIsChangeCooldown(true);
		}
	}

	public function isAvailable(string $spellName): bool {
		if ($this->getSpellObject($spellName)->getChangeCount() == 0) {
			return false;
		}

		return true;
	}

	public function startCastingSpell(string $castToType, int $castToNum, Spell $spell) {
		Details::log(TimeTicker::getInstance()->getCombatTimer() . ": Start cast " . $spell->getName());

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