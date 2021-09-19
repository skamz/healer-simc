<?php

namespace Rotations;

abstract class EnumSpells {

	abstract public function getAliases(): array;

	public function getSpellClass(int $spellNum): string {
		$spellList = static::getAliases();
		$spellListFlip = array_flip($spellList);
		if (empty($spellListFlip[$spellNum])) {
			throw new \Exception("неизвестный спелл {$spellNum}");
		}
		return $spellListFlip[$spellNum];
	}

	public function getSpellNum(string $spellName): int {
		$spellList = static::getAliases();
		if (empty($spellList[$spellName])) {
			throw new \Exception("неизвестный спелл");
		}
		return $spellList[$spellName];
	}

}