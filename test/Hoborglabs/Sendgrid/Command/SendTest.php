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
			'-b', '@' . __DIR__ . '/../../../fixtures/emailbody',
			// other required options
			'-t', 'test@test.com',
			'-s', 'test subject'
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
			// other required options
			'-b', 'email body',
			'-s', 'test subject'
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

	/** @test */
	public function shouldSendEmailToListOfEmailsFromAFile() {
		$sendGridMock = $this->getSendGridMock();
		$args = [
			'-t', '@' . __DIR__ . '/../../../fixtures/addresses',
			// other minimal config
			'-b', 'email body',
			'-s', 'test subject'
		];

		$assertEmail = function($email) {
			$this->assertEquals($email->to, [ 'test1@test.com', 'test2@test.com' ]);

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
	public function shouldSendEmailWithSpecifiedSubject() {
		$sendGridMock = $this->getSendGridMock();
		$args = [
			'-s', 'this is a test subject',
			// other minimal config
			'-t', 'test@test.com',
			'-b', 'email body',
		];

		$assertEmail = function($email) {
			$this->assertEquals($email->subject, 'this is a test subject');

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
