<?php


namespace Spells;


class HealSpell extends \Spell {

	public function getSpellPower() {
		throw new \Exception("getSpellPower not set");
	}

	public function getHealAmount() {
		$return = \Player::getInstance()->getInt() * $this->getSpellPower();
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

}