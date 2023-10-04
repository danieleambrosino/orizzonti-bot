<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Handlers\ConversationEndedHandler;
use Bot\Presentation;
use Bot\Request;
use Bot\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ConversationEndedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class ConversationEndedHandlerTest extends TestCase
{
	public function testConversationEndedHandlerNoStartCommand(): void
	{
		// Given
		$handler = new ConversationEndedHandler();
		$request = new Request(text: 'test');
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNull($response);
	}

	public function testConversationEndedHandlerWithStartCommand(): void
	{
		// Given
		$handler = new ConversationEndedHandler();
		$request = new Request(
			text: '/mipresento',
			entities: [(object) ['type' => 'bot_command']],
		);
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNotNull($response);
		$this->assertSame('Ti sei già presentato!', $response->text);
		$this->assertFalse($response->forceReply);
	}
}
