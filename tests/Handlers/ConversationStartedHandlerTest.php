<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Handlers\ConversationStartedHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ConversationStartedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class ConversationStartedHandlerTest extends TestCase
{
	public function testConversationStartedHandlerNotStartCommand(): void
	{
		// Given
		$handler = new ConversationStartedHandler();
		$request = new Request();
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNull($response);
	}

	public function testConversationStartedHandlerWithStartCommand(): void
	{
		// Given
		$handler = new ConversationStartedHandler();
		$request = new Request(
			firstName: 'Pippo',
			text: '/mipresento',
			entities: [(object) ['type' => 'bot_command']],
		);
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNotNull($response);
		$this->assertInstanceOf(Response::class, $response);
		$this->assertSame('Ciao Pippo, benvenuto! Scrivi qui la tua presentazione:', $response->text);
		$this->assertTrue($response->forceReply);
	}
}
