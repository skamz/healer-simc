<?php

namespace Classes;

use Buffs\Paladin\Holy\HolyShockCritIncrease;

class ClassBuffer {

	public static function apply(string $spec) {
		self::{"apply_{$spec}"}();
	}

	public static function apply_paladin_holy() {
		\Player::getInstance()->addBuff(new HolyShockCritIncrease());
	}

}