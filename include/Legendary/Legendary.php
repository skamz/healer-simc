<?php

namespace Legendary;

abstract class Legendary {

	abstract public static function isActive(): bool;

	public static function isLegOnPlayer() {
		$activeLeg = get_class(\Player::getInstance()->getLegendary());
		return ($activeLeg == static::class);
	}
}