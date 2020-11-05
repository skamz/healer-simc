<?php


class Player extends Unit {

	private static $instance;

	protected $crit;
	protected $haste;
	protected $int;
	protected $mastery;
	protected $versatility;


	/**
	 * @var StatCalculator
	 */
	protected $statCalculator;

	public function __construct() {
		$this->mana = 10000;
		$this->health = 20800;
		$this->maxHealth = $this->health;
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getInt() {
		return $this->int;
	}

	public function getCritCount() {
		return $this->crit;
	}

	public function getHasteCount() {
		return $this->haste;
	}

	public function getMasteryCount() {
		return $this->mastery;
	}

	public function getVersatilityCount() {
		return $this->versatility;
	}

	public function setInt(int $count) {
		$this->int = $count;
		return $this;
	}

	public function setCrit(int $count) {
		$this->crit = $count;
		return $this;
	}

	public function setHaste(int $count) {
		$this->haste = $count;
		return $this;
	}

	public function setMatery(int $count) {
		$this->mastery = $count;
		return $this;
	}

	public function setVersatility(int $count) {
		$this->versatility = $count;
		return $this;
	}

	public function getStatCalculator(): StatCalculator {
		return $this->statCalculator;
	}

	public function setStatCalculator(StatCalculator $statCalculator) {
		$this->statCalculator = $statCalculator;
		return $this;
	}

	public function healTaken(int $amount) {
		parent::healTaken($amount);
		Place::registerHeal($amount);
	}


}