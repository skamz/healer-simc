<?php

namespace Spells\Paladin;

use Events\Event;
use Spells\Paladin\Holy\HPSpell;
use Spells\Paladin\Holy\JudgmentOfLight;

class Judgment extends HPSpell {

	protected float $cd = 12;
	protected bool $hasteIsReduceCd = true;
	protected bool $isDamageSpell = true;

	public function applySpecial() {
		// 25 случайных игроков получал хилку за 3-5 секунд
		parent::applySpecial();
		$healSpell = new JudgmentOfLight();
		$iterationFrom = \TimeTicker::getInstance()->getIteration();
		$iterationTo = $iterationFrom + 5 / \TimeTicker::TICK_COUNT;

		$healEvent = new Event(\Caster::class, "applySpellToPlayer");
		for ($i = 0; $i < JudgmentOfLight::TARGET_COUNT; $i++) {
			$addEvent = (clone $healEvent);
			$addEvent->setArgs([rand(0, 19), $healSpell]);

			$iterationStep = rand($iterationFrom, $iterationTo);
			\Events::getInstance()->registerEvent($iterationStep, $addEvent);
		}

	}


	public function getRealDamageSPParams(): array {
		return [
			1367 => 1910,
			1280 => 1788,
		];
	}

}