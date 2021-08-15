<?php


namespace Spells\Priest\Holy;


class Heal extends HolySpell {

	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 3.09704674;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}