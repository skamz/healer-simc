<?php


namespace Buffs\Priest;


use Events\Event;
use Spells\Priest\DC\PurgeWickedDot;
use Spells\Priest\DC\ShadowWordPainDot;

class ShadowWordPain extends \Debuff {

	const TICK_CD = 2;
	protected float $duration = 16;

	private float $lastTickPercent;
	private float $tickDuration;

	public function reApply(\Buff $buff) {
		\Events::getInstance()->removeEvent($this->getFadeEventId());
		$addTime = min($this->duration, $buff->duration * 0.33);
		$this->setDuration($buff->duration + $addTime);
		echo "Reapply dot. New duration: " . $this->duration . "<br>\n";
	}

	public function registerTickEvent() {
		$event = new Event($this, "applyTick");
		$iteration = \TimeTicker::getInstance()->getIteration() + ceil($this->getTickCooldown() / \TimeTicker::TICK_COUNT);
		if ($iteration < $this->iterationEnd) {
			\Events::getInstance()->registerEvent($iteration, $event);
		}
	}

	public function applyTick() {
		$dot = new ShadowWordPainDot();
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
		$dot = new ShadowWordPainDot();
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