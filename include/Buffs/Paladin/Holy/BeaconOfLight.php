<?php

namespace Buffs\Paladin\Holy;

use Player;
use Spell;
use Spells\Paladin\Holy\HolyShock;
use Spells\Paladin\Holy\LightOfMartyr;

class BeaconOfLight extends \Buff {

	const HEAL_PERCENT_SINGLE = 50;
	const HEAL_PERCENT_DOUBLE = 30;

	const SPELL_TRIGGER_LIST = [
		LightOfMartyr::class,
		HolyShock::class
	];



}