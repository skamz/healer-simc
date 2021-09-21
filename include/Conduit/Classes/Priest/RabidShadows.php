<?php


namespace Mediums\Ventir\Conduits;


class RabidShadows extends Conduit {

	public function apply() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Covenant\RabidShadows());
	}

}