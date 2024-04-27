<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Enums\Status;
use Bot\Handlers\InviterRequestedHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(InviterRequestedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class InviterRequestedHandlerTest extends TestCase
{
	public function testInviterRequestedHandler(): void
	{
		// Given
		$handler = new InviterRequestedHandler();
		$request = new Request(text: 'test');
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
}
