<?php


namespace Pets;


class Pet extends \Unit {

	/**
	 * $liveTime=null Существует все время
	 * @var float|null
	 */
	protected float $liveTime;

	/**
	 * Список способностей питомца
	 * @var array
	 */
	protected array $spells = [];

	public function __construct() {
		$this->setTarget(\SpellState::CAST_TO_ENEMY, \Place::getInstance()->getRandomEnemy());
	}

	public function __destruct() {
		#echo \TimeTicker::getInstance()->getCombatTimer() . " pet die\n";
		$this->onExpire();
	}

	public function tick() {
		if (isset($this->liveTime)) {
			$this->liveTime -= \TimeTicker::TICK_COUNT;
		}
	}

	public function isExpire() {
		if (isset($this->liveTime)) {
			return $this->liveTime <= 0;
		}
		return false;
	}

	public function onExpire() {

	}

}