<?php

namespace Traits;

trait Singleton {

	private static $instance;

	public static function getInstance(): self {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}