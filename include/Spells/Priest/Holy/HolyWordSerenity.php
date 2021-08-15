<?php


namespace Spells\Priest\Holy;


class HolyWordSerenity extends HolySpell {

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 7.35;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}