<?php


namespace Mediums\Ventir\Conduits;


class ShiningRadiance extends Conduit {

	public function apply() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Covenant\ShiningRadiance());
	}

}