<?php

declare(strict_types=1);

namespace Tests\Unit\Dto;

use Bot\Dto\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Response::class)]
class ResponseTest extends TestCase
{
	public function testCreation(): void
	{
		$response = new Response('test', true);
		$this->assertSame('test', $response->text);
		$this->assertTrue($response->forceReply);

		$response = new Response('test', false);
		$this->assertSame('test', $response->text);
		$this->assertFalse($response->forceReply);
	}

	public function testToTelegramResponseNoForceReply(): void
	{
		$response = new Response('test text', false);
		$chatId = $messageId = 1;
		$this->assertSame([
			'method' => 'sendMessage',
			'chat_id' => $chatId,
			'text' => $response->text,
			'reply_to_message_id' => $messageId,
		], $response->toTelegramResponse($chatId, $messageId));
	}

	public function testToTelegramResponseForceReply(): void
	{
		$response = new Response('test text', true);
		$chatId = $messageId = 1;
		$this->assertSame([
			'method' => 'sendMessage',
			'chat_id' => $chatId,
			'text' => $response->text,
			'reply_to_message_id' => $messageId,
			'reply_markup' => [
				'force_reply' => true,
				'selective' => true,
			],
		], $response->toTelegramResponse($chatId, $messageId));
	}
}
