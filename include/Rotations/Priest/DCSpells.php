<?php

namespace Rotations\Priest;


use Spells\Priest\AscendedBlast;
use Spells\Priest\AscendedNova;
use Spells\Priest\BoonOfAscended;
use Spells\Priest\DC\Halo;
use Spells\Priest\DC\Mindbender;
use Spells\Priest\DC\MindBlast;
use Spells\Priest\DC\Mindgames;
use Spells\Priest\DC\Penance;
use Spells\Priest\DC\PowerWordRadiance;
use Spells\Priest\DC\PowerWordSolace;
use Spells\Priest\DC\PurgeWicked;
use Spells\Priest\DC\Schism;
use Spells\Priest\DC\Shadowfiend;
use Spells\Priest\DC\ShadowWordPain;
use Spells\Priest\PowerWordShield;
use Spells\Priest\Smite;

class DCSpells {

	const PENANCE = 1;
	const SCHISM = 2;
	const SMITE = 3;
	const RADIANCE = 4;
	const SHIELD = 5;
	const MINDGAMES = 6;
	const SOLACE = 7;
	const PURGE_WICKED = 8;
	const MIND_BLAST = 9;
	const HALO = 10;
	const MINDBENDER = 11;
	const SHADOW_PAIN = 12;
	const BOON_OF_ASCENDED = 13;
	const ASCENDED_BLAST = 14;
	const ASCENDED_NOVA = 15;
	const SHADOWFIEND = 16;

	public static function getAliases(): array {
		return [
			Schism::class => self::SCHISM,
			Mindgames::class => self::MINDGAMES,
			Halo::class => self::HALO,
			Penance::class => self::PENANCE,
			PowerWordSolace::class => self::SOLACE,
			PowerWordRadiance::class => self::RADIANCE,
			PowerWordShield::class => self::SHIELD,
			PurgeWicked::class => self::PURGE_WICKED,
			MindBlast::class => self::MIND_BLAST,
			Smite::class => self::SMITE,
			Mindbender::class => self::MINDBENDER,
			ShadowWordPain::class => self::SHADOW_PAIN,
			BoonOfAscended::class => self::BOON_OF_ASCENDED,
			AscendedBlast::class => self::ASCENDED_BLAST,
			AscendedNova::class => self::ASCENDED_NOVA,
			Shadowfiend::class => self::SHADOWFIEND,
		];
	}

	public static function getSpellClass(int $spellNum): string {
		$spellList = self::getAliases();
		$spellListFlip = array_flip($spellList);
		if (empty($spellListFlip[$spellNum])) {
			throw new \Exception("неизвестный спелл {$spellNum}");
		}
		return $spellListFlip[$spellNum];
	}

	public static function getSpellNum(string $spellName): int {
		$spellList = self::getAliases();
		if (empty($spellList[$spellName])) {
			throw new \Exception("неизвестный спелл");
		}
		return $spellList[$spellName];
	}

}