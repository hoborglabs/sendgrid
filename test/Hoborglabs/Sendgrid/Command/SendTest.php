<?php
namespace Hoborglabs\Sendgrid\Command;

use Mockery;

class SendTests extends \PHPUnit_Framework_TestCase {

	public function tearDown() {
		Mockery::close();
	}

	/** @test */
	public function shouldSendEmailFromBodyFile() {
		$sendGridMock = $this->getSendGridMock();
		$args = [
			'-t', 'test@test.com',
			'-f', __DIR__ . '/../../../fixtures/emailbody'
		];

		$assertEmail = function($email) {
			$this->assertEquals(
				$email->text,
				file_get_contents(__DIR__ . '/../../../fixtures/emailbody')
			);

			return true;
		};

		$sendGridMock
			->shouldReceive('send')
				->with(\Mockery::on($assertEmail))
				->once();

		$send = new Send([ 'from' => 'test@test.com' ], $sendGridMock);
		$send->run($args);
	}

	/** @test */
	public function shouldAllowToSpecifyToAddress() {
		$sendGridMock = $this->getSendGridMock();
		$args = [
			'-t', 'test@test.com',
			'-f', __DIR__ . '/../../../fixtures/emailbody'
		];

		$assertEmail = function($email) {
			$this->assertEquals($email->to, [ 'test@test.com' ]);

			return true;
		};

		$sendGridMock
			->shouldReceive('send')
				->with(\Mockery::on($assertEmail))
				->once();

		$send = new Send([ 'from' => 'test@test.com' ], $sendGridMock);
		$send->run($args);
	}

	protected function getSendGridMock() {
		return $sendGridMock = Mockery::mock('\SendGrid[send]', [ 'testUser', 'testPassword' ]);
	}
}
