<?php


namespace Rotations;


class BaseRotation {

	protected array $spellBook = [];

	public function __construct() {
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


}