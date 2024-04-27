<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Enums\Status;
use Bot\Handlers\ConversationEndedHandler;
use Bot\Handlers\ConversationStartedHandler;
use Bot\Handlers\Factory;
use Bot\Handlers\InviterRequestedHandler;
use Bot\Handlers\PresentationRequestedHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Factory::class)]
class FactoryTest extends TestCase
{
	public function testHandlersFactory(): void
	{
		$this->assertInstanceOf(
			ConversationStartedHandler::class,
			Factory::create(Status::ConversationStarted),
		);
		$this->assertInstanceOf(
			PresentationRequestedHandler::class,
			Factory::create(Status::PresentationRequested),
		);
		$this->assertInstanceOf(
			InviterRequestedHandler::class,
			Factory::create(Status::InviterRequested),
		);
		$this->assertInstanceOf(
			ConversationEndedHandler::class,
			Factory::create(Status::ConversationEnded),
		);
	}
}
