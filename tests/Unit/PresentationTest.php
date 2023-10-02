<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Presentation;
use Bot\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Presentation::class)]
class PresentationTest extends TestCase
{
	public function testMethods(): void
	{
		// Given
		$presentation = new Presentation(1);

		// When
		$presentation
			->setUsername('pippobaudo')
			->setFirstName('Pippo')
			->setLastName('Baudo')
			->setPresentation('presentation')
			->setInviter('inviter')
			->setStatus(Status::ConversationStarted)
		;

		// Then
		$this->assertSame(1, $presentation->userId);
		$this->assertSame('pippobaudo', $presentation->username);
		$this->assertSame('Pippo', $presentation->firstName);
		$this->assertSame('Baudo', $presentation->lastName);
		$this->assertSame('presentation', $presentation->presentation);
		$this->assertSame('inviter', $presentation->inviter);
		$this->assertSame(Status::ConversationStarted, $presentation->status);
	}

	public function testPassingValidNumberAsStatus(): void
	{
		// Given
		$presentation = new Presentation(1, status: 0);

		// Then
		$this->assertSame(Status::ConversationStarted, $presentation->status);
	}

	public function testPassingInvalidNumberAsStatus(): void
	{
		$this->expectException(\ValueError::class);
		$this->expectExceptionMessageMatches('/is not a valid backing value/');
		new Presentation(1, status: -1);
	}

	public function testSerialize(): void
	{
		// Given
		$presentation = new Presentation(
			userId: 1,
			username: 'pippobaudo',
			firstName: 'Pippo',
			lastName: 'Baudo',
			status: Status::ConversationStarted,
			presentation: 'presentation',
			inviter: 'inviter',
		);

		// When
		$serialized = $presentation->__serialize();

		// Then
		$this->assertSame([
			'userId' => 1,
			'username' => 'pippobaudo',
			'firstName' => 'Pippo',
			'lastName' => 'Baudo',
			'status' => Status::ConversationStarted->value,
			'presentation' => 'presentation',
			'inviter' => 'inviter',
		], $serialized);
	}

	public function testUnserialize(): void
	{
		// Given
		$presentation = new Presentation(0);
		$data = [
			'userId' => 1,
			'username' => 'pippobaudo',
			'firstName' => 'Pippo',
			'lastName' => 'Baudo',
			'status' => Status::ConversationStarted->value,
			'presentation' => 'presentation',
			'inviter' => 'inviter',
		];

		// When
		$presentation->__unserialize($data);

		// Then
		$this->assertSame(1, $presentation->userId);
		$this->assertSame('pippobaudo', $presentation->username);
		$this->assertSame('Pippo', $presentation->firstName);
		$this->assertSame('Baudo', $presentation->lastName);
		$this->assertSame('presentation', $presentation->presentation);
		$this->assertSame('inviter', $presentation->inviter);
		$this->assertSame(Status::ConversationStarted, $presentation->status);
	}
}
