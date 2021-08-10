<?php


namespace Spells\Priest\DC;


use Buffs\Priest\Atonement;
use Spells\SpellSchool\Holy;

class PowerWordRadiance extends DcSpell {

	protected float $manaPercentCost = 6.5;
	protected float $castTime = 2;
	protected float $gcd = 1.5;
	protected float $cd = 20;
	protected int $targetCount = 5;
	protected int $changeCount = 2;
	protected int $maxChangeCount = 2;
	protected bool $hasteIsReduceCastTime = true;
	protected string $spellSchool = Holy::class;

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

	public function getSpellCommonTargets(int $playerNum): array {
		$playersNum = \Place::getInstance()->getPlayerWithoutBuff(Atonement::class);
		shuffle($playersNum);
		$playersNum = array_diff($playersNum, [$playerNum]);
		$return = array_slice($playersNum, 0, $this->targetCount - 1);
		$return[] = $playerNum;
		return $return;
	}

}