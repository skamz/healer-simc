<?php


namespace Spells\Priest\Holy;


class CircleHealing extends HolySpell {

	protected int $targetCount = 5;

	// уменьшает кд
	// дает баф при тале
	public function getHealAmount(): int {
		$return = \Player::getInstance()->getInt() * 1.102417726;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}