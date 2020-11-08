<?php


namespace Spells\Priest\DC;


class PowerWordRadiance extends DcSpell {

	protected float $manaPercentCost = 6.5;
	protected float $castTime = 2;
	protected float $gcd = 1.5;
	protected float $cd = 20;
	protected int $targetCount = 5;
	protected int $changeCount = 2;
	protected int $maxChangeCount = 2;
	protected bool $hasteIsReduceCastTime = true;

	// искупление вины 60% длительностью
	public function getHealAmount() {
		$return = \Player::getInstance()->getInt() * 1.05;
		$return = $this->applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseHeal", $return, $this);
		return $return;
	}

	public function applyBuffs(): array {
		$atonement = new \Buffs\Priest\Atonement();
		$duration = $atonement->getDuration() * 0.6;
		$atonement->setDuration($duration);
		return [
			$atonement,
		];
	}

}