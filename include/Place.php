<?php


class Place {

	use \Traits\Singleton;

	const ANALYZE_PLAYER = 0;

	/**
	 * Массив игроков
	 * @var array Player[]
	 */
	protected array $players = [];

	/**
	 * @var array \Pets\Pet[]
	 */
	protected array $pets = [];

	/**
	 * Масссив противников
	 * @var array
	 */
	protected array $enemies = [];

	protected static $totalHeal;

	public function getMyPlayer(): Player {
		return $this->players[self::ANALYZE_PLAYER];
	}

	public function getAllPlayers() {
		return $this->players;
	}

	public function addPlayer(Player $player): self {
		$this->players[] = $player;
		return $this;
	}

	public function getPlayersAllNums(): array {
		return array_keys($this->players);
	}

	public function getPlayerWithoutBuff(string $buffName) {
		$return = [];
		foreach ($this->players as $num => $player) {
			if ($player->hasBuff($buffName) !== null) {
				continue;
			}
			$return[] = $num;
		}
		return $return;
	}

	public function getRandomNumPlayerWithoutBuff(string $buffName): int {
		$list = $this->players;
		$playerKeys = array_keys($list);
		shuffle($playerKeys);
		foreach ($playerKeys as $num) {
			$player = $this->players[$num];
			/** @var $player Player */
			if ($player->hasBuff($buffName) !== null) {
				continue;
			}
			return $num;
		}
		return $num;
	}

	public function getRandomNumPlayer(): int {
		$list = $this->getRandomNumPlayers(1);
		return array_shift($list);
	}

	public function getRandomNumPlayers(int $count, array $include = [], array $excludeRandom = []): array {
		$list = array_keys($this->players);
		$list = array_diff($list, $excludeRandom);
		shuffle($list);
		return $include + array_slice($list, 0, $count);
	}

	public function getPlayer(int $num): Player {
		return $this->players[$num];
	}

	public function savePlayer(int $num, Player $player) {
		$this->players[$num] = $player;
	}

	public function addEnemy(Enemy $enemy) {
		$this->enemies[] = $enemy;
	}

	public function getRandomEnemy(): int {
		$list = array_keys($this->enemies);
		shuffle($list);
		return array_shift($list);
	}

	public function getEnemy(int $num): Enemy {
		return $this->enemies[$num];
	}

	public function getEnemiesAllNums(): array {
		return array_keys($this->enemies);
	}

	public function saveEnemy(int $num, Enemy $enemy) {
		$this->enemies[$num] = $enemy;
	}

	public function addPet(\Pets\Pet $pet): self {
		$this->pets[] = $pet;
		return $this;
	}

	public function getPets(): array {
		return $this->pets;
	}

	public function savePets(array $pets) {
		$this->pets = $pets;
	}

	public static function registerHeal(int $amount) {
		self::$totalHeal += $amount;
	}

	public static function getTotalHeal() {
		return self::$totalHeal;
	}

}