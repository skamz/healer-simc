<?php

namespace Resources;

use mysql_xdevapi\Exception;

abstract class Resource {

	protected int $count = 0;
	protected int $maxCount;

	public function inc(int $count = 1) {
		$this->count = min($this->maxCount, $this->count + $count);
	}

	public function dec(int $count) {
		if ($this->count < $count) {
			throw new Exception("Resource count {$this->count}, try to dec {$count}");
		}
		$this->count -= $count;
	}

	public function getCount(): int {
		return $this->count;
	}

}