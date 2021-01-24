<?php


class RedisManager {

	const ROTATIONS = "rotations";
	const SIM_RESULTS = "sim_results";
	const FUTURE_ROTATION = "future_rotation";
	const AVG_RESULT = "avg_result";

	const STAT_INT = "stat_int";
	const STAT_CRIT = "stat_crit";
	const STAT_HASTE = "stat_haste";
	const STAT_VERSA = "stat_versa";
	const STAT_MASTERY = "stat_mastery";

	use \Traits\Singleton;

	protected Redis $redis;

	public function __construct() {
		$this->redis = new Redis();
		$this->redis->connect("localhost");
	}

	public function sadd($key, $string) {
		$this->redis->sAdd($key, $string);
	}

	public function spop($key, int $count = 1) {
		return $this->redis->sPop($key);
	}

	public function smembers($key) {
		return $this->redis->sMembers($key);
	}

	public function scard($key) {
		return $this->redis->sCard($key);
	}

	public function incr($key) {
		$this->redis->incr($key);
	}

	public function get($key) {
		return $this->redis->get($key);
	}

	public function set($key, $value) {
		return $this->redis->set($key, $value);
	}

	public function del($key) {
		return $this->redis->del($key);
	}

	public static function getStatCalcKeys($type) {
		return [
			"data" => "json_rotation_data_{$type}",
			"progress" => "json_rotation_progress_{$type}",
		];
	}

}