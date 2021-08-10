<?php

namespace Spells\SpellSchool;

class SpellSchool {

	public static function getSchool() {
		echo self::class . "<br/>";
		$parts = explode('\\', static::class);
		return array_pop($parts);

	}

}