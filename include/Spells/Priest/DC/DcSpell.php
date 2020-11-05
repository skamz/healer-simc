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
			$buffNum = $checkPlayer->hasBuff(new Atonement());
			if (isset($buffNum)) {
				$healAmount = Atonement::getHealAmount($damageCount);
				$checkPlayer->healTaken($healAmount);
			}
			\Place::getInstance()->savePlayer($playerNum, $checkPlayer);
		}
	}

}