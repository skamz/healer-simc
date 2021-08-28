<?php

namespace Spells\Priest;

use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Arcane;

class AscendedNova extends DcSpell {

	const BOON_OF_ASCENDED_STACKS = 1;

	protected int $targetCount = 6;
	protected string $spellSchool = Arcane::class;
	protected float $gcd = 1;
	protected bool $isDamageSpell = true;
	protected bool $isTriggeredAtonement = true;

	public static function isAvailable(): bool {
		$cdAvailable = parent::isAvailable();
		if (!$cdAvailable) {
			return false;
		}
		$hasBuff = \Place::getInstance()->getMyPlayer()->hasBuff(\Buffs\Priest\BoonOfAscended::class);
		return (bool)$hasBuff;
	}

	public function applyBuffs(): array {
		\Player::getInstance()->getBuff(\Buffs\Priest\BoonOfAscended::class)->increaseStackCount(self::BOON_OF_ASCENDED_STACKS);
		return [];
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * $this->getRealSP(self::SP_TYPE_DAMAGE);
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

	public function getRealDamageSPParams(): array {
		return [
			1456 => 1042,
			1370 => 932,
			1309 => 933,
			1288 => 896,
			1227 => 844,
		];
	}
}
