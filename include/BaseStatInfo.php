<?php


class BaseStatInfo {

	public function getPointPenalty(): array {
		return [
			["points" => 30, "penalty" => 0],
			["points" => 9, "penalty" => 0.1],
			["points" => 8, "penalty" => 0.2],
			["points" => 7, "penalty" => 0.3],
			["points" => 12, "penalty" => 0.4],
			["points" => 60, "penalty" => 0.5],
			["points" => 9999999, "penalty" => 1],
		];
	}

	protected function getBasePoint() {
		throw new Exception("getPointPercents Not set");
	}

	public function getPointPercents() {
		throw new Exception("getPointPercents Not set");
	}


}