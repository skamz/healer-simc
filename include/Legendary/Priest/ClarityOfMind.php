<?php

namespace Legendary\Priest;

use Buffs\Priest\Rapture;
use Legendary\Legendary;

class ClarityOfMind extends Legendary {

	const INC_ATONEMENT_BUFF_TIME = 6;

	public function __construct() {

	}

	public static function isActive(): bool {
		$activeLeg = get_class(\Player::getInstance()->getLegendary());
		if ($activeLeg == self::class) {
			return (bool)\Player::getInstance()->hasBuff(Rapture::class);
		}
		return false;
	}

}