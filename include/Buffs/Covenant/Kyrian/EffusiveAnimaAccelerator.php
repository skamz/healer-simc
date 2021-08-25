<?php

namespace Buffs\Covenant\Kyrian;

use Events\Event;

class EffusiveAnimaAccelerator extends \Debuff {

	const TICK_CD = 2;
	protected float $duration = 8;
	private float $lastTickPercent;
	private $isOnApply = true;

	public function registerTickEvent() {
		if ($this->isOnApply) {
			\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), new EffusiveAnimaAcceleratorDot());
			$this->isOnApply = false;
		}
		$event = new Event($this, "applyTick");
		$iteration = \TimeTicker::getInstance()->getIteration() + ceil($this->getTickCooldown() / \TimeTicker::TICK_COUNT);
		if ($iteration < $this->iterationEnd) {
			\Events::getInstance()->registerEvent($iteration, $event);
		}
	}

	public function applyTick() {
		$dot = new EffusiveAnimaAcceleratorDot();
		\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), $dot);
		$this->calcLastTickPercent();
		$this->registerTickEvent();
	}

	protected function calcLastTickPercent() {
		$iterationTick = $this->getTickCooldown() / \TimeTicker::TICK_COUNT;
		$iterationTickEnd = \TimeTicker::getInstance()->getIteration() + $iterationTick;
		if ($iterationTickEnd > $this->iterationEnd) {
			$diff = $this->iterationEnd - \TimeTicker::getInstance()->getIteration();
			$this->lastTickPercent = $diff / $iterationTick;
		}
	}

	public function applyFade() {
		$dot = new EffusiveAnimaAcceleratorDot();
		$dot->setDamageModifier($this->lastTickPercent);
		\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), $dot);
	}

	protected function getTickCooldown() {
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();
		return self::TICK_CD / (1 + $hastePercent / 100);
	}


}