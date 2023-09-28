<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Handlers\ConversationEndedHandler;
use Bot\Handlers\ConversationStartedHandler;
use Bot\Handlers\Factory;
use Bot\Handlers\InviterRequestedHandler;
use Bot\Handlers\PresentationRequestedHandler;
use Bot\Presentation;
use Bot\Response;
use Bot\Status;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ConversationStartedHandler::class)]
#[CoversClass(PresentationRequestedHandler::class)]
#[CoversClass(InviterRequestedHandler::class)]
#[CoversClass(ConversationEndedHandler::class)]
#[CoversClass(Factory::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Response::class)]
#[UsesFunction('Bot\isStartCommand')]
class HandlersTest extends TestCase
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

	public function testPresentationRequestedHandler(): void
	{
		// Given
		$handler = new PresentationRequestedHandler();
		$request = json_decode(json_encode([
			'message' => [
				'text' => 'test',
			],
		]));
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNotNull($response);
		$this->assertInstanceOf(Response::class, $response);
		$this->assertSame('Da chi sei stato invitato?', $response->text);
		$this->assertTrue($response->forceReply);
		$this->assertSame('test', $presentation->presentation);
		$this->assertSame(Status::InviterRequested, $presentation->status);
	}

	public function testInviterRequestedHandler(): void
	{
		// Given
		$handler = new InviterRequestedHandler();
		$request = json_decode(json_encode([
			'message' => [
				'text' => 'test',
			],
		]));
		$presentation = new Presentation(1);

		// When
		$response = $handler->handle($request, $presentation);

		// Then
		$this->assertNotNull($response);
		$this->assertInstanceOf(Response::class, $response);
		$this->assertSame('Grazie!', $response->text);
		$this->assertFalse($response->forceReply);
		$this->assertSame('test', $presentation->inviter);
		$this->assertSame(Status::ConversationEnded, $presentation->status);
	}

	public function testConversationEndedHandlerNoStartCommand(): void
	{
		// Given
		$handler = new ConversationEndedHandler();
		$request = json_decode(json_encode([
			'message' => [
				'text' => 'test',
			],
		]));
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
		$request = json_decode(json_encode([
			'message' => [
				'message_id' => 1,
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
		$this->assertSame('Ti sei già presentato!', $response->text);
		$this->assertFalse($response->forceReply);
	}

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
