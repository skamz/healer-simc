<?php


namespace Spells\Priest\DC;


class SinsMany extends DcSpell {

	public function __construct() {
		\Place::getInstance()->getMyPlayer()->addBuff(new \Buffs\Priest\SinsMany());
	}

}