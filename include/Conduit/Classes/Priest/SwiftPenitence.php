<?php


namespace Mediums\Ventir\Conduits;


#https://www.wowhead.com/spell=337891/swift-penitence
class SwiftPenitence extends Conduit {

	public function apply() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Covenant\SwiftPenitence());
	}
}