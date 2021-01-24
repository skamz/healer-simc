<?php


namespace Buffs\Priest;


class SinsMany extends \Buff {

	public function getIncrementPercent() {
		$atonementCount = \Helper::getCountPlayersWithBuff(Atonement::class);
		if ($atonementCount <= 1) {
			return 12;
		} elseif ($atonementCount <= 2) {
			return 10;
		} elseif ($atonementCount <= 3) {
			return 8;
		} elseif ($atonementCount <= 4) {
			return 7;
		} elseif ($atonementCount <= 5) {
			return 6;
		} elseif ($atonementCount <= 6) {
			return 6;
		} elseif ($atonementCount <= 7) {
			return 5;
		} elseif ($atonementCount <= 8) {
			return 4;
		} elseif ($atonementCount <= 9) {
			return 4;
		} elseif ($atonementCount >= 10) {
			return 3;
		}
		return 0;
	}

	public function applyOnDamage(int $damageCount, \Spell $fromSpell = null): int {
		$percent = 1 + $this->getIncrementPercent() / 100;
		return round($damageCount * $percent);
	}
}