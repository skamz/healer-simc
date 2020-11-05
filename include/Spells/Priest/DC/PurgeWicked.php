<?php


namespace Spells\Priest\DC;


/**
 * Class PurgeWicked Очищение зла https://www.wowhead.com/spell=204197/purge-the-wicked
 * @package Spells\Priest\DC
 */
class PurgeWicked extends DcSpell {

	protected float $manaPercentCost = 1.8;
	protected float $gcd = 1.5;
	protected bool $isTriggeredAtonement = true;

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.248;
		return \Spell::applySecondary($return);
	}

	public function getDotAmount() {
		$return = \Player::getInstance()->getInt() * $this->getTickCount() * 0.137;
		return \Spell::applySecondary($return);
	}

	public function getTickCount() {
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();
		return 20 / (2 / (1 + $hastePercent / 100));
	}
}