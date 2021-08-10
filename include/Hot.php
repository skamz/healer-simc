<?php


use Events\Event;

class Hot extends Buff {

	protected float $tickCd;
	private float $lastTickPercent;

	public function getTickSP() {
		throw new Exception("Hot Tick Spell Power not set");
	}

	protected function getTickCooldown() {
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();
		return $this->tickCd / (1 + $hastePercent / 100);
	}

	public function registerTickEvent() {
		$event = new Event($this, "applyTick");
		$iteration = \TimeTicker::getInstance()->getIteration() + ceil($this->getTickCooldown() / \TimeTicker::TICK_COUNT);
		if ($iteration < $this->iterationEnd) {
			\Events::getInstance()->registerEvent($iteration, $event);
		}
	}

	public function applyTick() {
		$hot = new \Spells\Druid\Restor\RejuvenationTick();
		Caster::hotHealPlayer($this->unit, $hot);

		$this->calcLastTickPercent();
		$this->registerTickEvent();
	}

	public function applyFade() {
		$hot = new \Spells\Druid\Restor\RejuvenationTick();
		$hot->setDamageModifier($this->lastTickPercent);
		Caster::hotHealPlayer($this->unit, $hot);
	}

	protected function calcLastTickPercent() {
		$iterationTick = $this->getTickCooldown() / \TimeTicker::TICK_COUNT;
		$iterationTickEnd = \TimeTicker::getInstance()->getIteration() + $iterationTick;
		if ($iterationTickEnd > $this->iterationEnd) {
			$diff = $this->iterationEnd - \TimeTicker::getInstance()->getIteration();
			$this->lastTickPercent = $diff / $iterationTick;
		}
	}
}