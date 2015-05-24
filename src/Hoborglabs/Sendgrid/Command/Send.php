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
				'opt' => [ '-f', '--body-file' ],
				'name' => 'bodyFile'
			],
			[
				'opt' => [ '-b', '--body' ],
				'name' => 'body'
			]
		];
	}

	protected function createEmail($args) {
		$email = new Email();
		$email
			->addTo('foo@bar.com')
			->setFrom($this->config['from'])
			->setSubject('')
			->setText($args['body']);

		return $email;
	}

	protected function sendEmail(Email $email) {
		try {
			$this->sendGrid->send($email);
		} catch(\SendGrid\Exception $e) {
			echo $e->getCode();
			foreach($e->getErrors() as $er) {
				echo $er;
			}
		}
	}

	protected function processArguments($args) {
		$arguments = [];
		$options = $this->getOptions();

		for ($i = 0; $i < count($args); $i += 2) {
			$opt = $this->getOption($args[$i], $options);

			$arguments[$opt['name']] = $args[$i+1];
		}

		if (isset($arguments['bodyFile'])) {
			$arguments['body'] = file_get_contents($arguments['bodyFile']);
		}

		return $arguments;
	}

	protected function getOption($key, $options) {
		foreach ($options as $option) {
			if ( !in_array($key, $option['opt'])) {
				continue;
			}

			return $option;
		}

		throw new \Exception("Unknown option '${key}'");
	}
}
