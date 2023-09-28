<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Response::class)]
class ResponseTest extends TestCase
{
	public function testResponse(): void
	{
		$response = new Response('test', true);
		$this->assertSame('test', $response->text);
		$this->assertTrue($response->forceReply);

		$response = new Response('test', false);
		$this->assertSame('test', $response->text);
		$this->assertFalse($response->forceReply);
	}
}
