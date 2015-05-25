<?php
namespace Hoborglabs\SendGrid\Command;

use SendGrid\Email;

class Send {

	public function __construct($config, $sendGrid) {
		$this->config = $config;
		$this->sendGrid = $sendGrid;
	}

	public function run($args = []) {
		$args = $this->processArguments($args);
		$email = $this->createEmail($args);

		$this->sendEmail($email);
	}

	public function getOptions() {
		return [
			[
				'opt' => [ '-t', '--to' ],
				'name' => 'to',
				'type' => 'array'
			],
			[
				'opt' => [ '-b', '--body' ],
				'name' => 'body',
				'type' => 'string'
			]
		];
	}

	protected function createEmail($args) {
		$email = new Email();
		$email
			->setSubject('')
			->setFrom($this->config['from'])
			->setText($args['body']);

		foreach ($args['to'] as $to) {
			$email->addTo($to);
		}

		return $email;
	}

	protected function sendEmail(Email $email) {
		try {
			$this->sendGrid->send($email);
		} catch(\SendGrid\Exception $e) {
			echo $e->getCode();
			foreach ($e->getErrors() as $er) {
				echo $er;
			}
		}
	}

	protected function processArguments($args) {
		$arguments = [];
		$options = $this->getOptions();

		for ($i = 0; $i < count($args); $i += 2) {
			$opt = $this->getOptionByKey($args[$i], $options);
			$val = $args[$i+1];

			if ('@' == $val[0]) {
				$val = file_get_contents(substr($val, 1));
			}

			if ('array' == $opt['type']) {
				$val = $this->processArrayArgument($args[$i+1], $opt);

				if (!isset($arguments[$opt['name']])) {
					$arguments[$opt['name']] = [ ];
				}

				array_splice($arguments[$opt['name']], 0, 0, $val);
			} else {
				$arguments[$opt['name']] = $val;
			}
		}

		return $arguments;
	}

	protected function processArrayArgument($val, $option) {
		// split file content by new line
		if ('@' == $val[0]) {
			$val = file(substr($val, 1), FILE_IGNORE_NEW_LINES);
		}

		if (is_string($val)) {
			$val = [ $val ];
		}

		return $val;
	}

	protected function getOptionByKey($key, $options) {
		foreach ($options as $option) {
			if (!in_array($key, $option['opt'])) {
				continue;
			}

			return $option;
		}

		throw new \Exception("Unknown option '${key}'");
	}
}
