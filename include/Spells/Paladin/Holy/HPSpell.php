<?php

namespace Spells\Paladin\Holy;

abstract class HPSpell extends \Spell {

	protected bool $isTriggerBeaconHeal = false;
	protected int $gainHolyPower = 0;
	protected static int $costHolyPower = 0;

	public static function isAvailable(): bool {
		if (!parent::isAvailable()) {
			return false;
		}

		if (self::$costHolyPower > 0) {
			$hpRes = \Player::getInstance()->getHolyPowerResource();
			if ($hpRes->getCount() < self::$costHolyPower) {
				return false;
			}
		}
		return true;
	}

}