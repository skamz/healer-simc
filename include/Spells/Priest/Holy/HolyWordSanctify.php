<?php


namespace Spells\Priest\Holy;


class HolyWordSanctify extends HolySpell {

	protected int $targetCount = 6;

	public function getHealAmount() {
		$return = \Player::getInstance()->getInt() * 2.571918991;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}