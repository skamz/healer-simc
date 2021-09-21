<?php

namespace Buffs\Paladin\Holy;

/**
 * Предсмертный вздох Мараада. Легендарный эффект
 * Увеличивает на 10% отхил Света мучениа и дополнительно хилит частиты света за стак???
 */
class MaraadsDyingBreath extends \Buff {

	const INC_PER_STACK = 10;

	protected float $duration = 10;

}