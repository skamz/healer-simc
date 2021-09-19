<?php

namespace Spells\Paladin\Holy;

abstract class HPSpell extends \Spell {

	protected bool $isTriggerBeaconHeal = false;
	protected int $gainHolyPower = 0;
	protected static int $costHolyPower = 0;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(static::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(static::SP_TYPE_HEAL);
		$return = self::applyVersatility($return);



		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [];
	}

	public function getRealHealSPParams(): array {
		return [];
	}

	public static function isAvailable(): bool {
		if (!parent::isAvailable()) {
			return false;
		}

		if (static::$costHolyPower > 0) {
			$hpRes = \Player::getInstance()->getHolyPowerResource();
			\Details::log("Player holy_power: {$hpRes->getCount()}; Spell cost: ".static::$costHolyPower);
			if ($hpRes->getCount() < static::$costHolyPower) {
				return false;
			}
		}
		return true;
	}

	public function applySpecial() {
		parent::applySpecial();
		if ($this->gainHolyPower > 0) {
			\Player::getInstance()->getHolyPowerResource()->inc($this->gainHolyPower);
		}
		if (static::$costHolyPower > 0) {
			\Player::getInstance()->getHolyPowerResource()->dec(static::$costHolyPower);
		}
	}

}