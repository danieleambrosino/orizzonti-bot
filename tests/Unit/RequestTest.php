<?php

declare(strict_types=1);

namespace Tests;

use Bot\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Request::class)]
class RequestTest extends TestCase
{
	public function testCreateFromInvalidJson(): void
	{
		$input = '{'; // invalid JSON string
		$this->expectException(\JsonException::class);

		Request::fromJsonString($input);
	}

	public function testCreateWithInvalidSchema(): void
	{
		/** @var non-empty-string */
		$input = json_encode([
			'message' => [
				'hello' => 'hi',
			],
		]);
		$this->expectException(\InvalidArgumentException::class);

		Request::fromJsonString($input);
	}

	public function testCreateCorrectly(): void
	{
		/** @var non-empty-string */
		$input = json_encode([
			'message' => [
				'message_id' => 1,
				'from' => [
					'id' => 1,
					'username' => 'pippobaudo',
					'first_name' => 'Pippo',
					'last_name' => 'Baudo',
				],
				'chat' => [
					'id' => 1,
				],
				'text' => 'test',
			],
		]);

		$request = Request::fromJsonString($input);
		$this->assertInstanceOf(Request::class, $request);
	}

	public function testIsStartCommandReturnsFalse(): void
	{
		$this->assertFalse((new Request())->isStartCommand());
		$this->assertFalse((new Request(entities: []))->isStartCommand());
		$this->assertFalse((new Request(entities: [(object) [
			'type' => 'wrong_type',
		]]))->isStartCommand());
		$this->assertFalse((new Request(
			entities: [(object) [
				'type' => 'bot_command',
			]],
			text: 'asdf',
		))->isStartCommand());
	}

	public function testIsStartCommand(): void
	{
		$this->assertTrue((new Request(
			text: '/mipresento',
			entities: [(object) ['type' => 'bot_command']],
		))->isStartCommand());
	}
}
