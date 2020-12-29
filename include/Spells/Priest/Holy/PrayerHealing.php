<?php


namespace Spells\Priest\Holy;


class PrayerHealing extends HolySpell {

	protected int $targetCount = 5;

	public function getHealAmount() {
		$return = \Player::getInstance()->getInt() * 0.9184759945;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}