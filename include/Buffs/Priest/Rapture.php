<?php

namespace Buffs\Priest;

use Mediums\Ventir\Conduits\Exaltation;

class Rapture extends \Buff {

	protected float $duration = 8;

	public function __construct() {
		//@todo Реализация не очень, надо перенести в сам баф/кондуит через callback
		if (\Place::getInstance()->getMyPlayer()->hasConduit(Exaltation::class)) {
			$this->duration += 1;
		}
	}

}