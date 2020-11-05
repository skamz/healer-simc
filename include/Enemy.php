<?php


class Enemy extends Unit {

	public function __construct() {
		$this->health = 9999999999999;
		$this->maxHealth = $this->health;
	}

}