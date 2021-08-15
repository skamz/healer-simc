<?php


namespace Spells\Priest\Holy;


class PrayerMending extends HolySpell {

	protected int $targetCount = 5;

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 0.64;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}