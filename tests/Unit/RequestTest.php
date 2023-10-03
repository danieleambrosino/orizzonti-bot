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
		$input = json_encode([
			'message' => [
				'from' => [
					'id' => 1,
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
		$this->assertTrue(Request::fromJsonString(json_encode([
			'message' => [
				'from' => [
					'id' => 1,
					'first_name' => 'Pippo',
				],
				'chat' => [
					'id' => 1,
				],
				'entities' => [
					[
						'type' => 'bot_command',
					],
				],
				'text' => '/mipresento',
			],
		]))->isStartCommand());
	}
}
