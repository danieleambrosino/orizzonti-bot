<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Bot\Dto\Request;
use Bot\Dto\Response;
use Bot\Entities\Presentation;
use Bot\Enums\Status;
use Bot\Handlers\PresentationRequestedHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PresentationRequestedHandler::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class PresentationRequestedHandlerTest extends TestCase
{
	public function testPresentationRequestedHandler(): void
	{
		// Given
		$handler = new PresentationRequestedHandler();
		$request = new Request(text: 'test');
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
}
