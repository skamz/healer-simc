<?php


abstract class Buff {

	const FULL_TIME = 9999;

	/**
	 * Время действия бафа
	 * @var float
	 */
	protected float $duration = self::FULL_TIME;
	protected float $baseDuration;

	protected float $tickTimer;
	protected float $lostToTick;

	protected int $startIterationNum;
	protected int $iterationEnd = 0;
	protected int $stackCount = 1;
	protected int $maxStackCount = 1;

	protected array $eventsId = [];

	public Unit $unit;

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
		$this->startIterationNum = TimeTicker::getInstance()->getIteration();
		$this->baseDuration = $this->duration;
		$this->calcIterationEnd();
	}

	protected function calcIterationEnd() {
		$this->iterationEnd = TimeTicker::getInstance()->getIteration() + intval($this->duration / TimeTicker::TICK_COUNT);
	}

	public function getIterationEnd() {
		if (!isset($this->iterationEnd)) {
			echo get_class($this) . "<br>";
		}
		return $this->iterationEnd;
	}

	public function setFadeEventId(string $eventId) {
		$this->eventsId["fade"] = $eventId;
	}

	public function getFadeEventId() {
		return $this->eventsId["fade"];
	}

	public function getName() {
		return get_class($this);
	}

	public function setTickTimer(float $tickTimer) {
		$this->tickTimer = $tickTimer;
	}

	public function reApply(Buff $buff) {
		$this->setDuration($buff->duration);
		Events::getInstance()->registerBuffFade($this);
	}

	public function getDuration() {
		return $this->duration;
	}

	public function getBaseDuration() {
		return $this->baseDuration;
	}

	public function setDuration(float $duration) {
		$this->duration = $duration;
		$this->calcIterationEnd();
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

	public function onHealTaken(int $amount, Spell $fromSpell, Player $player) {
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

	public function applyIntAmount(int $amount, Spell $fromSpell = null): float {
		return $amount;
	}

	public function applyCritAmount(int $amount, Spell $fromSpell = null): float {
		return $amount;
	}

	public function applyMasteryAmount(int $amount, Spell $fromSpell = null): float {
		return $amount;
	}

	public function applyHasteAmount(int $amount, Spell $fromSpell = null): float {
		return $amount;
	}

	public function applyVersatilityAmount(int $amount, Spell $fromSpell = null): float {
		return $amount;
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

	public function registerTickEvent() {

	}

	public function increaseStackCount(int $intCount): int {
		if ($this->stackCount < $this->maxStackCount) {
			$this->stackCount = min($this->stackCount + $intCount, $this->maxStackCount);
			Details::log("Inc buff " . get_class($this) . " stack count. To: " . $this->stackCount);
		}
		return $this->stackCount;
	}

	public function getStackCount(): int {
		return $this->stackCount;
	}

}