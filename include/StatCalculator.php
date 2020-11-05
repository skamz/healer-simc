<?php


class StatCalculator {

	private static $playerInstance;

	/**
	 * @var CritCalculator
	 */
	private $critCalculator;

	/**
	 * @var HasteCalculator
	 */
	private $hasteCalculator;

	/**
	 * @var MasteryCalculator
	 */
	private $masteryCalculator;

	/**
	 * @var VersatilityCalculator
	 */
	private $versaCalculator;

	public function __construct(CritCalculator $crit, HasteCalculator $haste, MasteryCalculator $mastery, VersatilityCalculator $versa) {
		$this->critCalculator = $crit;
		$this->hasteCalculator = $haste;
		$this->masteryCalculator = $mastery;
		$this->versaCalculator = $versa;
	}

	public function getCritPercent(int $critCount = null) {
		if (!isset($critCount)) {
			$critCount = Player::getInstance()->getCritCount();
		}
		return $this->critCalculator->calcPercent($critCount);
	}

	public function getMasteryPercent(int $masteryCount = null) {
		if (!isset($masteryCount)) {
			$masteryCount = Player::getInstance()->getMasteryCount();
		}
		return $this->masteryCalculator->calcPercent($masteryCount);
	}

	public function getHastePercent(int $hasteConut = null) {
		if (!isset($hasteConut)) {
			$hasteConut = Player::getInstance()->getHasteCount();
		}
		return $this->hasteCalculator->calcPercent($hasteConut);
	}

	public function getVersatilityPercent(int $versatilityCount = null) {
		if (!isset($versatilityCount)) {
			$versatilityCount = Player::getInstance()->getVersatilityCount();
		}
		return $this->versaCalculator->calcPercent($versatilityCount);
	}

}