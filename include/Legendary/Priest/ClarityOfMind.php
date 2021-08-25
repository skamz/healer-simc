<?php

namespace Legendary\Priest;

use Buffs\Priest\Rapture;
use Legendary\Legendary;

class ClarityOfMind extends Legendary {

	//6 секунд если тал Ева, 3 сек при щит души
	const INC_ATONEMENT_BUFF_TIME = 6;

	public function __construct() {

	}

	public static function isLegOnPlayer() {
		$activeLeg = get_class(\Player::getInstance()->getLegendary());
		return ($activeLeg == self::class);
	}

	public static function isActive(): bool {
		if (self::isLegOnPlayer()) {
			return (bool)\Player::getInstance()->hasBuff(Rapture::class);
		}
		return false;
	}

}