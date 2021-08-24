<?php

namespace Mediums;

abstract class Medium {

	public function __construct() {
		$buffList = $this->getApplyBuffs();
		foreach ($buffList as $buffClassName) {
			\Place::getInstance()->getMyPlayer()->addBuff(new $buffClassName());
		}
	}

	public function castTrigger(\Spell $spell) {
		$map = $this->getTriggerMap();
		$className = get_class($spell);
		if (empty($map[$className])) {
			return false;
		}
		foreach ($map[$className] as $callback) {
			$this->$callback();
		}
	}

	abstract function getTriggerMap(): array;

	abstract function getApplyBuffs(): array;

}