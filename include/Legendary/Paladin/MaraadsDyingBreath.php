<?php

namespace Legendary\Paladin;

use Legendary\Legendary;

class MaraadsDyingBreath extends Legendary {

	public static function isActive(): bool {
		return self::isLegOnPlayer();
	}
}