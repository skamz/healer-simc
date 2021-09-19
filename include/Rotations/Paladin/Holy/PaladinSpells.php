<?php

namespace Rotations\Paladin\Holy;

class PaladinSpells extends \Rotations\EnumSpells {

	const JUDGMENT = 1;
	const CRUSADER_STRIKE = 2;
	const HAMMER_OF_WRATH = 3;
	const HOLY_SHOCK = 4;
	const LIGHT_OF_DAWN = 5;
	const LIGHT_OF_MARTYR = 6;

	public function getAliases(): array {
		return [
			\Spells\Paladin\Judgment::class => self::JUDGMENT,
			\Spells\Paladin\CrusaderStrike::class => self::CRUSADER_STRIKE,
			\Spells\Paladin\HammerOfWrath::class => self::HAMMER_OF_WRATH,
			\Spells\Paladin\Holy\HolyShock::class => self::HOLY_SHOCK,
			\Spells\Paladin\Holy\LightOfDawn::class => self::LIGHT_OF_DAWN,
			\Spells\Paladin\Holy\LightOfMartyr::class => self::LIGHT_OF_MARTYR,
		];
	}

}