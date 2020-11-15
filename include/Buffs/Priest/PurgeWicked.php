<?php


namespace Buffs\Priest;


use Events\Event;
use Spells\Priest\DC\PurgeWickedDot;

class PurgeWicked extends \Debuff {

	const TICK_CD = 2;
	protected float $duration = 20;

	private float $lastTickPercent;
	private float $tickDuration;

	public function reApply(\Buff $buff) {
		$addTime = min($this->duration, $buff->duration * 0.33);
		$this->setDuration($buff->duration + $addTime);
		echo "Reapply dot. New duration: " . $this->duration . "<br>";
	}

	public function registerTickEvent() {
		$event = new Event($this, "applyTick");
		$iteration = \TimeTicker::getInstance()->getIteration() + $this->getTickCooldown() / \TimeTicker::TICK_COUNT;
		\Events::getInstance()->registerEvent($iteration, $event);
	}

	public function applyTick() {
		if (!isset($this->tickDuration)) {
			$this->tickDuration = $this->getTickCooldown();
			return true;
		}
		$this->tickDuration -= \TimeTicker::TICK_COUNT;
		if ($this->tickDuration <= 0) {
			$dot = new PurgeWickedDot();
			\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), $dot);

			$this->tickDuration = $this->getTickCooldown();
			if ($this->tickDuration > $this->duration) {
				$this->lastTickPercent = $this->duration / $this->tickDuration;
			}
		}
	}

	public function applyFade() {
		$dot = new PurgeWickedDot();
		$dot->setDamageModifier($this->lastTickPercent);
		\Caster::castSpellToEnemy(\Place::getInstance()->getRandomEnemy(), $dot);

	}

	protected function getTickCooldown() {
		$hastePercent = \Player::getInstance()->getStatCalculator()->getHastePercent();
		return self::TICK_CD / (1 + $hastePercent / 100);
	}

	protected function getTickCooldownPercent() {

	}

}