<?php

declare(strict_types=1);

namespace Tests;

use Bot\Response;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function Bot\decodeInput;
use function Bot\getTelegramResponse;
use function Bot\isStartCommand;

/**
 * @internal
 */
#[CoversFunction('Bot\decodeInput')]
#[CoversFunction('Bot\isStartCommand')]
#[CoversFunction('Bot\getTelegramResponse')]
#[UsesClass(Response::class)]
class FunctionsTest extends TestCase
{
	public function testDecodeInputInvalidJson(): void
	{
		$input = '{'; // invalid JSON string
		$this->expectException(\InvalidArgumentException::class);

		decodeInput($input);
	}

	public function testDecodeInputInvalidSchema(): void
	{
		$input = '{}'; // valid JSON but missing 'message' field
		$this->expectException(\InvalidArgumentException::class);

		decodeInput($input);
	}

	public function testDecodeInputValidData(): void
	{
		$input = json_encode([
			'message' => [
				'hello' => 'hi',
			],
		]);

		$decodedInput = decodeInput($input);
		$this->assertIsObject($decodedInput);
		$this->assertNotEmpty($decodedInput->message);
	}

	public function testIsStartCommandEmptyRequest(): void
	{
		$this->assertFalse(isStartCommand(new \stdClass()));
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'hello' => 'hi',
			],
		]))));
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => [],
			],
		]))));
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => null,
			],
		]))));
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => '',
			],
		]))));
	}

	public function testIsStartCommandWrongEntity(): void
	{
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => [
					[
						'type' => 'wrong_type',
					],
				],
			],
		]))));
	}

	public function testIsStartCommandWrongText(): void
	{
		$this->assertFalse(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => [
					[
						'type' => 'bot_command',
					],
				],
				'text' => 'wrong_text',
			],
		]))));
	}

	public function testIsStartCommand(): void
	{
		$this->assertTrue(isStartCommand(json_decode(json_encode([
			'message' => [
				'entities' => [
					[
						'type' => 'bot_command',
					],
				],
				'text' => '/mipresento',
			],
		]))));
	}

	public function testGetTelegramResponseNoForceReply(): void
	{
		$response = new Response('test text', false);
		$chatId = $messageId = 1;
		$this->assertSame([
			'method' => 'sendMessage',
			'chat_id' => $chatId,
			'text' => $response->text,
			'reply_to_message_id' => $messageId,
		], getTelegramResponse($response, $chatId, $messageId));
	}

	public function testGetTelegramResponseForceReply(): void
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
		], getTelegramResponse($response, $chatId, $messageId));
	}
}
