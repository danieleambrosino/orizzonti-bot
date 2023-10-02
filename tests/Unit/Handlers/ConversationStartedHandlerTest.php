<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Handlers\ConversationStartedHandler;
use Bot\Presentation;
use Bot\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ConversationStartedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Response::class)]
#[UsesFunction('Bot\isStartCommand')]
class ConversationStartedHandlerTest extends TestCase
{
	public function testConversationStartedHandlerNotStartCommand(): void
	{
		// Given
		$handler = new ConversationStartedHandler();
		$request = json_decode(json_encode([
			'message' => [
				'message_id' => 1,
				'from' => [
					'id' => 1,
					'first_name' => 'Pippo',
				],
				'text' => 'not_start_command',
			],
		]));
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
		$request = json_decode(json_encode([
			'message' => [
				'message_id' => 1,
				'from' => [
					'id' => 1,
					'first_name' => 'Pippo',
				],
				'text' => '/mipresento',
				'entities' => [
					[
						'type' => 'bot_command',
					],
				],
			],
		]));
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