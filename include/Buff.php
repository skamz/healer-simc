<?php


class Buff {

	/**
	 * Время действия бафа
	 * @var float
	 */
	protected float $duration;

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

	public function setTickTimer(float $tickTimer) {
		$this->tickTimer = $tickTimer;
	}

	public function reApply(Buff $buff) {
		$this->duration = $buff->duration;
	}

	public function getBaseDuration() {
		return $this->duration;
	}

	public function setBaseDuration(float $ducation) {
		$this->duration = $ducation;
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

	public function applyOnDamage(int $damageCount) {
		return $damageCount;
	}

	public function applyOnHeal(int $healCount) {
		return $healCount;
	}

	public function applyIntPercents(float $percents): float {
		return $percents;
	}

	public function applyCritPercents(float $percents): float {
		return $percents;
	}

	public function applyhastePercents(float $percents): float {
		return $percents;
	}

	public function applyMasteryPercents(float $percents): float {
		return $percents;
	}

	public function applyVersatilityPercents(float $percents): float {
		return $percents;
	}

}