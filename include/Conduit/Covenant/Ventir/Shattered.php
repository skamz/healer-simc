<?php


namespace Mediums\Ventir\Conduits;


class Shattered extends Conduit {

	public function apply() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Covenant\Shattered());
	}

}