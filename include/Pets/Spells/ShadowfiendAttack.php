<?php


namespace Pets\Spells;


use Buffs\Covenant\RabidShadows;
use Spells\Priest\DC\DcSpell;
use Spells\SpellSchool\Physical;

class ShadowfiendAttack extends DcSpell {

	protected bool $isTriggeredAtonement = true;
	protected float $cd = 1.5;
	protected bool $hasteIsReduceCd = true;
	protected string $spellSchool = Physical::class;

	public function __construct() {
		parent::__construct();
		//@todo Хуяевая реализация, нужно сделаьт через метод самого бафа
		if (\Player::getInstance()->hasBuff(\Buffs\Covenant\RabidShadows::class) !== null) {
			$this->cd *= 1 - RabidShadows::PERCENT / 100;
		}
	}

	public function getDamageAmount() {
		$return = \Player::getInstance()->getInt() * 0.44642;
		$return = \Spell::applySecondary($return);
		$return = \Player::getInstance()->applyBuffs("increaseDamage", $return, $this);
		return $return;
	}

}