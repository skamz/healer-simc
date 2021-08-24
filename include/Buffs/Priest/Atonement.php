<?php


namespace Buffs\Priest;
//Affected Spells: Smite (585), Shadow Word: Pain (589), Mind Blast (8092), Holy Fire (14914), Mind Flay (15407), Shadow Word: Death (32379), Vampiric Touch (34914), Penance (47540), Penance (47666), Mind Sear (48045), Mind Sear (49821), Holy Word: Chastise (88625), Halo (120696), Divine Star (122128), Power Word: Solace (129250), Holy Nova (132157), Shadowy Apparition (148859), Purge the Wicked (204197), Purge the Wicked (204213), Shadow Word: Void (205351), Shadow Crash (205386), Void Bolt (205448), Schism (214621), Void Eruption (228360), Void Eruption (228361), Void Torrent (263165), Mindgames (323673), Mindgames (323707), Ascended Nova (325020), Unholy Transfusion (325203), Ascended Blast (325283), Ascended Eruption (325326), Devouring Plague (335467), Searing Nightmare (341385), Void Bolt (343355), Mind Sear (344754), Shadow Weaving (346111), Ascended Eruption (347625)
//

class Atonement extends \Buff {

	protected float $duration = 15;

	public function __construct() {

	}

	public static function getHealAmount(int $damageAmount): int {
		$masteryPercent = \Place::getInstance()->getMyPlayer()->getStatCalculator()->getMasteryPercent();
		$healPercent = 0.5 * (1 + $masteryPercent / 100);
		$return = $damageAmount * $healPercent;
		return $return;
	}

}