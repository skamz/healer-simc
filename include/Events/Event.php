<?php

namespace Events;

class Event {

	protected $object;
	protected string $callback;
	protected array $args;

	public function __construct($object, string $callback, ...$args) {
		$this->object = $object;
		$this->callback = $callback;
		$this->args = $args;
	}

	/**
	 * @param array $args
	 */
	public function setArgs(array $args): void {
		$this->args = $args;
	}

	public function getObject() {
		return $this->object;
	}

	public function getArgs() {
		return $this->args;
	}

	public function apply() {
		call_user_func_array([$this->object, $this->callback], $this->args);
	}

	public function applyBuffEvent() {
		throw new \Exception("Event not set");
	}

	public function applyPlayerEvent() {
		throw new \Exception("Event not set");
	}

}