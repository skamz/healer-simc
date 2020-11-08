<?php


class Buff {

	/**
	 * Время действия бафа
	 * @var float
	 */
	protected float $duration = 9999;
	protected float $baseDuration;

	protected float $tickTimer;
	protected float $lostToTick;

	/**
	 * Объем поглощения урона
	 * @var
	 */
	protected int $absorbAmount;

	/**
	 * @var StatCalculator
	 */
	protected StatCalculator $statCalculator;

	public function __construct() {
		$this->baseDuration = $this->duration;
	}

	public function getName() {
		return get_class($this);
	}

	public function setTickTimer(float $tickTimer) {
		$this->tickTimer = $tickTimer;
	}

	public function reApply(Buff $buff) {
		$this->duration = $buff->duration;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function getBaseDuration() {
		return $this->baseDuration;
	}

	public function setDuration(float $duration) {
		$this->duration = $duration;
	}

	public function applyTick() {
	}

	public function tick() {
		if ($this->isEnd()) {
			return false;
		}
		$this->applyTick();
		$this->duration -= TimeTicker::TICK_COUNT;
	}

	public function isEnd(): bool {
		return $this->duration <= 0;
	}

	public function applyFade() {

	}

	public function applyOnDamage(int $damageCount, Spell $fromSpell = null) {
		return $damageCount;
	}

	public function applyOnHeal(int $healCount, Spell $fromSpell = null) {
		return $healCount;
	}

	public function applyIntPercents(float $percents, Spell $fromSpell = null): float {
		return $percents;
	}

	public function applyCritPercents(float $percents, Spell $fromSpell = null): float {
		return $percents;
	}

	public function applyHastePercents(float $percents, Spell $fromSpell = null): float {
		return $percents;
	}

	public function applyMasteryPercents(float $percents, Spell $fromSpell = null): float {
		return $percents;
	}

	public function applyVersatilityPercents(float $percents, Spell $fromSpell = null): float {
		return $percents;
	}

	public function increaseDamage(int $damageCount, Spell $fromSpell = null): int {
		return $damageCount;
	}

	public function increaseHeal(int $healCount, Spell $fromSpell = null): int {
		return $healCount;
	}

	public static function isTimeRefreshBuffOnEnemy(int $enemyNum) {
		$enemyObj = Place::getInstance()->getEnemy($enemyNum);
		$buffNum = $enemyObj->hasBuff(get_called_class());
		if (isset($buffNum)) {
			$buffObj = $enemyObj->getBuffByNum($buffNum);
			if ($buffObj->getDuration() / $buffObj->getBaseDuration() < 0.33) {
				return true;
			}
			return false;
		}
		return true;
	}

}