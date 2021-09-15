<?php

namespace stat_sourse\specs\priest_dc;

class PriestDcMasteryInfo extends \BaseMasteryInfo {

	public function getPointPercents() {
		return 1.5;
	}
}

$obj = new PriestDcMasteryInfo();
return $obj;
