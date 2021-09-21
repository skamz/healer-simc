<?php


class Events {

	use \Traits\Singleton;

	const UNIT_TYPE_PLAYER = 1;
	const UNIT_TYPE_ENEMY = 2;

	const TYPE_BUFF = 1;
	const TYPE_PLAYER = 2;

	protected array $events = [];

	public function registerEvent(int $iterationStep, \Events\Event $event): string {
		if (empty($this->events[$iterationStep])) {
			$this->events[$iterationStep] = [];
		}
		$this->events[$iterationStep][] = $event;
		return "{$iterationStep}_" . array_key_last($this->events[$iterationStep]);
	}

	public function applyEvents($iteration) {
		if (empty($this->events[$iteration])) {
			return;
		}
		foreach ($this->events[$iteration] as $eventObj) {
			/** @var $eventObj \Events\Event */
			$eventObj->apply();
		}
		unset($this->events[$iteration]);
	}

	public function removeEvent($eventId) {
		[$iterationStep, $eventNum] = explode("_", $eventId, 2);
		if (isset($this->events[$iterationStep][$eventNum])) {
			unset($this->events[$iterationStep][$eventNum]);
		}
	}

	public function prolongBuffByName(string $buffName, int $addSteps) {
		$players = Place::getInstance()->getAllPlayers();
		/** @var Player $player */
		foreach ($players as $player) {
			/** @var Buff $buff */
			foreach ($player->getBuffs() as $buff) {
				if ($buff->getName() == $buffName) {
					$this->moveBuffFade($buff->getFadeEventId(), $addSteps);
				}
			}
		}
	}


	public function moveBuffFade($eventId, int $addSteps): ?string {
		[$iterationStep, $eventNum] = explode("_", $eventId, 2);
		if (isset($this->events[$iterationStep][$eventNum])) {
			/** @var \Events\Event $event */
			$event = $this->events[$iterationStep][$eventNum];
			unset($this->events[$iterationStep][$eventNum]);
			/** @var Unit $unit */
			$unit = $event->getObject();
			[$buffNum] = $event->getArgs();
			$buff = $unit->getBuffByNum($buffNum);
			$iterationEnd = $buff->getIterationEnd();

			return $this->registerEvent($iterationEnd + $addSteps, $event);
		}
		return null;
	}

	public function removeBuffFade(Buff $buff) {
		Events::getInstance()->removeEvent($buff->getFadeEventId());
	}

	public function registerBuffFade(Buff $buff) {
		$this->removeBuffFade($buff);

		$buffNum = $buff->unit->hasBuff(get_class($buff));
		$fadeEvent = new \Events\Event($buff->unit, "fadeBuff", $buffNum);
		$iterationEnd = TimeTicker::getInstance()->getIterationAfterTime($buff->getDuration());
		$eventId = Events::getInstance()->registerEvent($iterationEnd, $fadeEvent);
		$buff->setFadeEventId($eventId);
	}

}