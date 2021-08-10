<?php


namespace Rotations;


class BaseRotation {

	protected array $talents = [];
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

	public function setTalents($tier, $spell) {
		if (!empty($this->talents[$tier])) {
			exit("Талант не может быть выбран, уже взят другой из этой строки");
		}
		$this->talents[$tier] = true;
		$this->addSpell($spell);
	}



}