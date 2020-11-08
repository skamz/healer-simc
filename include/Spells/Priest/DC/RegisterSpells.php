<?php


namespace Spells\Priest\DC;

class RegisterSpells extends \Spells\Priest\RegisterSpells {

	public static function run() {
		parent::run();
		\SpellState::getInstance()->registerSpell(new Penance())
			->registerSpell(new PowerWordRadiance())
			->registerSpell(new PurgeWicked())
			->registerSpell(new Shadowfiend())
			->registerSpell(new ShadowMend())
			->registerSpell(new Schism())
			->registerSpell(new PowerInfusion());
	}

}