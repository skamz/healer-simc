<?php


class Spell {

	const PING = 0.07;

	/**
	 * Время восстановления заклинания
	 * @var float
	 */
	protected float $cd = 0;

	/**
	 * Влияет ли haste на скорость восстановления заклинания
	 * @var bool
	 */
	protected bool $hasteIsReduceCd = false;

	/**
	 * Глобальное КД заклинания
	 * @var float
	 */
	protected float $gcd = 1.5;

	/**
	 * Влияет ли haste на скорость восстановления заклинания
	 * @var bool
	 */
	protected bool $hasteIsReduceGCd = true;

	/**
	 * Длительность применения заклинания
	 * @var float
	 */
	protected float $duration;

	/**
	 * Влияет ли haste на скорость восстановления заклинания
	 * @var bool
	 */
	protected bool $hasteIsReduceDuration = false;

	/**
	 * Время применения заклинания
	 * @var float
	 */
	protected float $castTime = 0;

	/**
	 * Влияет ли haste на скорость восстановления заклинания
	 * @var bool
	 */
	protected bool $hasteIsReduceCastTime = false;

	/**
	 * Сколько плюха летит до таргета
	 * @var float
	 */
	protected float $travelTime = 0;

	/**
	 * Сколько процентов маны стоит применение заклинания
	 * @var float
	 */
	protected float $manaPercentCost = 0;

	/**
	 * Количество целей на которые применяется заклинание
	 * @var int
	 */
	protected int $targetCount = 1;

	/**
	 * Количество зарядок у заклинания
	 * @var int
	 */
	protected int $changeCount = 1;

	/**
	 * Максимальное количество зарядок у заклинания
	 * @var int
	 */
	protected int $maxChangeCount = 1;

	/**
	 * Массив бафов применяеных на цель
	 * @var array
	 */
	protected array $applyBuff = [];

	/**
	 * Массив дебафоф применяемых на цель
	 * @var array
	 */
	protected array $applyDebuff = [];

	/**
	 * Модификатор наносимого урона (например когда описание 60% урона)
	 * @var float|int
	 */
	protected float $damageModifier = 1;

	/**
	 * Модификатор исцеления (например когда описание 60% хила)
	 * @var float|int
	 */
	protected float $healModifier = 1;

	/**
	 * Если целей несколько, как выбираются остальные цели
	 * @var bool
	 */
	protected bool $isSmart = false;

	public function __construct() {
	}

	public function getDuration() {
		if (!$this->hasteIsReduceDuration) {
			return $this->duration;
		}
		$return = $this->applyHaste($this->duration);
		return round($return, 2);
	}

	public function getGcd() {
		if (!$this->hasteIsReduceGCd) {
			return $this->gcd;
		}
		$return = $this->applyHaste($this->gcd);
		return round($return, 2);
	}

	public function getCd() {
		if (!$this->hasteIsReduceCd) {
			return $this->cd;
		}
		$return = $this->applyHaste($this->cd);
		return round($return, 2);
	}

	public function getCastTime() {
		if (!$this->hasteIsReduceCastTime) {
			return $this->castTime;
		}
		$return = $this->applyHaste($this->castTime);
		return round($return + self::PING, 2);
	}

	protected function applyHaste($baseValue) {
		$hastePercents = Player::getInstance()->getStatCalculator()->getHastePercent();
		return $baseValue / (1 + $hastePercents / 100);
	}

	public function getDamageAmount() {
		throw new Exception("Наносимый урон не определен");
	}

	public function getHealAmount() {
		throw new Exception("Объем исцеления не определен");
	}

	public static function applyVersatility(float $amount) {
		$versaPercent = Player::getInstance()->getStatCalculator()->getVersatilityPercent();
		if ($versaPercent > 0) {
			$amount *= 1 + $versaPercent / 100;
		}
		return $amount;
	}

	public static function applyCrit(float $amount, $critCoefficient = 2) {
		$critPercent = Player::getInstance()->getStatCalculator()->getCritPercent();
		if (Helper::isProc($critPercent)) {
			return $amount * $critCoefficient;
		}
		return $amount;
	}

	public static function applySecondary(float $amount) {
		$amount = self::applyVersatility($amount);
		$amount = self::applyCrit($amount);
		return $amount;
	}

	public function applyBuffs(): array {
		return [];
	}

	public function afterDamage(int $damageCount) {
	}

	public function afterHeal(int $healCount) {
	}

	public function getTargetCount(): ?int {
		return $this->targetCount;
	}

	public static function isAvailable() {
		return SpellState::getInstance()->isAvailable(get_called_class());
	}

	public function getChangeCount() {
		return $this->changeCount;
	}

	public function getMaxChangeCount() {
		return $this->maxChangeCount;
	}

	public function incChangeCount() {
		$this->changeCount++;
		if ($this->changeCount > $this->maxChangeCount) {
			throw new Exception("changeCount > maxChangeCount. " . get_class($this));
		}
	}

	public function decChangeCount() {
		$this->changeCount--;
		if ($this->changeCount < 0) {
			throw new Exception("changeCount < 0. " . get_class($this));
		}
	}

	public function getName() {
		return get_class($this);
	}

	public function setDamageModifier(float $modifier) {
		$this->damageModifier = $modifier;
	}

	public function afterSuccessCast() {
	}

	public function getSpellCommonTargets(int $playerNum): array {
		return [$playerNum];
	}

}