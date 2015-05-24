<?php
namespace Hoborglabs\Sendgrid;

class Commands {

	protected $commands = [
		'send' => '\Hoborglabs\Sendgrid\Command\Send'
	];

	protected $config = [];

	public function __construct($config) {
		$this->config = $config;
	}

	public function createByName($name) {
		if (!isset($this->commands[$name])) {
			throw new \Exception("Unknown command '${name}'.");
		}

		$sendgrid = $this->getSendgrid();

		return new $this->commands[$name]($this->config, $sendgrid);
	}

	protected function getSendgrid() {
		return new \SendGrid($this->config['username'], $this->config['password']);
	}
}
