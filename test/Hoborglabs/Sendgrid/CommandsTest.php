<?php
namespace Hoborglabs\Sendgrid;

class CommandTests extends \PHPUnit_Framework_TestCase {

	/** @test
	 * @expectedException \Exception
	 * @expectedExceptionMessageRegExp #.*'notExisitingCommand'.*#
	 */
	public function shouldThrowExceptionForUnknownCommand() {
		$commands = $this->getCommandsObject();
		$commands->createByName('notExisitingCommand');
	}

	/** @test */
	public function shouldCreateSendCommand() {
		$commands = $this->getCommandsObject();
		$send = $commands->createByName('send');

		$this->assertInstanceOf('\Hoborglabs\Sendgrid\Command\Send', $send);
	}

	protected function getCommandsObject($config = []) {
		$minimalConfig = [
			'username' => 'testUser',
			'password' => 'testPassword',
		];
		$config = array_merge($config, $minimalConfig);

		return new Commands($config);
	}
}
