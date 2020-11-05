<?php


namespace Spells\Priest;


class RegisterSpells {

	public static function run() {
		\SpellState::getInstance()->registerSpell(new PowerWordShield())
			->registerSpell(new Smite());
	}

}