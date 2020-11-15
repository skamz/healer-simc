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

	public function removeEvent($eventId){
		[$iterationStep, $eventNum] = explode("_", $eventId, 2);
		if (isset($this->events[$iterationStep][$eventNum])) {
			unset($this->events[$iterationStep][$eventNum]);
		}
	}

	public function moveBuffFade($eventId): ?string {
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
			return $this->registerEvent($iterationEnd, $event);
		}
		return null;
	}

}