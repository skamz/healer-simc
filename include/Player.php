<?php


class Player extends Unit {

	private static $instance;

	protected int $crit;
	protected int $haste;
	protected int $int;
	protected int $mastery;
	protected int $versatility;
	protected string $covenant;
	protected \Mediums\Medium $covenantMedium;
	protected array $conduits = [];
	protected \Legendary\Legendary $legendary;

	protected array $resources = [];

	/**
	 * @var StatCalculator
	 */
	protected $statCalculator;

	public function __construct() {
		$this->mana = 10000;
		$this->health = 20800;
		$this->maxHealth = $this->health;
	}

	public static function getInstance(): self {
		return Place::getInstance()->getMyPlayer();
		/*if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;*/
	}

	public function getInt() {
		$return = $this->int;
		if ($this->hasBuff(\Buffs\GladiatorInsignia::class) !== null) {
			$return += \Buffs\GladiatorInsignia::INC_VALUE;
		}
		if ($this->hasBuff(\Buffs\CelestialGuidance::class) !== null) {
			$return *= 1 + \Buffs\CelestialGuidance::PERCENT / 100;
		}
		return $return;
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

	public function healTaken(int $amount, Spell $spell, Unit $unit = null) {
		parent::healTaken($amount, $spell, $this);
		Place::registerHeal($amount);
	}

	public function setConvenant(string $covenant) {
		$this->covenant = $covenant;
		return $this;
	}

	public function setCovenantMedium(string $medium) {
		$this->covenantMedium = new $medium();
		return $this;
	}

	public function setLegendary(string $className): self {
		$this->legendary = new $className();
		return $this;
	}

	public function getLegendary(): \Legendary\Legendary {
		return $this->legendary;
	}

	public function getMedium(): \Mediums\Medium {
		return $this->covenantMedium;
	}

	public function isVentir() {
		return $this->covenant == \Enum\Covenant::TYPE_VENTIR;
	}

	public function addConduit(\Mediums\Ventir\Conduits\Conduit $conduit): self {
		$conduit->apply();
		$this->conduits[] = $conduit;
		return $this;
	}

	public function hasConduit(string $conduitClass): bool {
		foreach ($this->conduits as $conduit) {
			if (get_class($conduit) == $conduit) {
				return true;
			}
		}
		return false;
	}

	public function registerResource(int $type, \Resources\Resource $resource): self {
		$this->resources[$type] = $resource;
		return $this;
	}

	public function getHolyPowerResource(): \Resources\Resource {
		if (empty($this->resources[\Resources\EnumList::PALADIN_HOLY_POWER])) {
			throw new Exception("Resource not set");
		}
		return $this->resources[\Resources\EnumList::PALADIN_HOLY_POWER];
	}

}