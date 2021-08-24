<?php


class StatCalculator {

	/**
	 * @var CritCalculator
	 */
	private CritCalculator $critCalculator;

	/**
	 * @var HasteCalculator
	 */
	private HasteCalculator $hasteCalculator;

	/**
	 * @var MasteryCalculator
	 */
	private MasteryCalculator $masteryCalculator;

	/**
	 * @var VersatilityCalculator
	 */
	private VersatilityCalculator $versaCalculator;

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
		$critCount = Player::getInstance()->applyBuffs("applyCritAmount", $critCount);
		$return = $this->critCalculator->calcPercent($critCount);
		$return = Player::getInstance()->applyBuffs("applyCritPercents", $return);
		return $return;
	}

	public function getMasteryPercent(int $masteryCount = null) {
		if (!isset($masteryCount)) {
			$masteryCount = Player::getInstance()->getMasteryCount();
		}
		$masteryCount = Player::getInstance()->applyBuffs("applyMasteryAmount", $masteryCount);
		$return = $this->masteryCalculator->calcPercent($masteryCount);
		$return = Player::getInstance()->applyBuffs("applyMasteryPercents", $return);
		return $return;
	}

	public function getHastePercent(int $hasteCount = null) {
		if (!isset($hasteCount)) {
			$hasteCount = Player::getInstance()->getHasteCount();
		}
		$hasteCount = Player::getInstance()->applyBuffs("applyHasteAmount", $hasteCount);
		$return = $this->hasteCalculator->calcPercent($hasteCount);
		$return = Player::getInstance()->applyBuffs("applyHastePercents", $return);
		return $return;

	}

	public function getVersatilityPercent(int $versatilityCount = null) {
		if (!isset($versatilityCount)) {
			$versatilityCount = Player::getInstance()->getVersatilityCount();
		}
		$versatilityCount = Player::getInstance()->applyBuffs("applyVersatilityAmount", $versatilityCount);
		$return = $this->versaCalculator->calcPercent($versatilityCount);
		$return = Player::getInstance()->applyBuffs("applyVersatilityPercents", $return);
		return $return;
	}

}