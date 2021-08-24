<?php

namespace Spells\Priest\DC;

use Buffs\Priest\Atonement;

class DcSpell extends \Spell {

	protected bool $isTriggeredAtonement = false;

	public function afterDamage(int $damageCount) {
		if (!$this->isTriggeredAtonement) {
			return false;
		}
		foreach (\Place::getInstance()->getPlayersAllNums() as $playerNum) {
			$checkPlayer = \Place::getInstance()->getPlayer($playerNum);
			$buffNum = $checkPlayer->hasBuff(Atonement::class);
			if (isset($buffNum)) {
				$healAmount = Atonement::getHealAmount($damageCount);
				$checkPlayer->healTaken($healAmount, Atonement::class);
			}
			\Place::getInstance()->savePlayer($playerNum, $checkPlayer);
		}
	}

	public function getRealHealSPParams(): array {
		throw new \Exception("getRealHealSPParams not set");
	}

	public function getRealDamageSPParams(): array {
		throw new \Exception("getRealDamageSPParams not set");
	}

	public function getDamageAmount() {
		throw new \Exception("getDamageAmount not set");
	}

	public function getHealAmount(): int {
		throw new \Exception("getHealAmount not set: ".get_class($this));
	}

}