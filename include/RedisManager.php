<?php


class RedisManager {

	const ROTATIONS = "rotations";
	const SIM_RESULTS = "sim_results";
	const FUTURE_ROTATION = "future_rotation";

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

}