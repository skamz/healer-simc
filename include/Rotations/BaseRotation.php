<?php


namespace Rotations;


use Buffs\Priest\Atonement;
use Exceptions\CombatTimeDone;

class BaseRotation {

	protected array $talents = [];
	protected array $spellBook = [];
	protected EnumSpells $spellList;

	public function __construct(EnumSpells $spellList) {
		$this->spellList = $spellList;
		$this->fillSpellBook();
	}

	protected function fillSpellBook() {

	}

	protected function addSpell(\Spell $spell) {
		$this->spellBook[get_class($spell)] = $spell;
		return $this;
	}

	/**
	 * Известен ли спел (талант)
	 * @param $spellName
	 * @return bool
	 */
	public function isKnowSpell($spellName) {
		if (isset($this->spellBook[$spellName])) {
			return true;
		}
		return false;
	}

	public function setTalents($tier, $spell) {
		if (!empty($this->talents[$tier])) {
			exit("Талант не может быть выбран, уже взят другой из этой строки");
		}
		$this->talents[$tier] = true;
		$this->addSpell($spell);
	}

	public function applySpell(int $spellNumber) {
		$spellClass = $this->spellList->getSpellClass($spellNumber);
		/** @var \Spell $spell */
		$spell = new $spellClass();

		while (\TimeTicker::getInstance()->tick()) {
			if (!\TimeTicker::getInstance()->isGcd() && !\TimeTicker::getInstance()->isCastingProgress()) {
				if ($spell->isAvailable()) {
					$toUnit = $this->getSpellTargetId($spell);
					\Caster::playerCastSpell($toUnit, $spell);
					return true;
				}
			}
		}
		throw new CombatTimeDone();
	}

	public function getSpellTargetId(\Spell $spell): int {
		// кто в таргете
		if ($spell->isDamageSpell()) {
			$toUnit = \Place::getInstance()->getRandomEnemy();
		} else {
			$toUnit = \Place::getInstance()->getRandomNumPlayerWithoutBuff(Atonement::class);
		}
		return $toUnit;
	}


	public function run($rotationInfoSteps) {
		while (!empty($rotationInfoSteps)) {
			$nextSpell = array_shift($rotationInfoSteps);
			$this->applySpell($nextSpell);
		}
	}

	public function waitGcd() {
		while (\TimeTicker::getInstance()->tick()) {
			if (!\TimeTicker::getInstance()->isGcd()) {
				break;
			}
		}
	}

	public function waitCasting() {
		while (\TimeTicker::getInstance()->tick()) {
			if (!\TimeTicker::getInstance()->isCastingProgress()) {
				break;
			}
		}
	}

	public function waitAvailableCast() {
		$this->waitGcd();
		$this->waitCasting();
	}

}