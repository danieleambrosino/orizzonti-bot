<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bot\Dao\ArrayDao;
use Bot\Dao\DaoInterface;
use Bot\Handlers\ConversationStartedHandler;
use Bot\Handlers\Factory;
use Bot\Presentation;
use Bot\Responder;
use Bot\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesFunction;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Responder::class)]
#[UsesClass(ArrayDao::class)]
#[UsesClass(ConversationStartedHandler::class)]
#[UsesClass(Factory::class)]
#[UsesClass(Presentation::class)]
#[UsesClass(Response::class)]
#[UsesFunction('Bot\isStartCommand')]
class ResponderTest extends TestCase
{
	private static DaoInterface $dao;
	private static Responder $responder;

	public static function setUpBeforeClass(): void
	{
		self::$dao = new ArrayDao();
		self::$responder = new Responder(self::$dao);
	}

	public function testIsInstantiatedCorrectly(): void
	{
		$this->assertInstanceOf(Responder::class, new Responder(new ArrayDao()));
	}

	public function testConversationStarted(): void
	{
		// Given
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

		// When
		$response = self::$responder->respond($request);

		// Then
		$this->assertIsString($response->text);
		$this->assertSame('Ciao Pippo, benvenuto! Scrivi qui la tua presentazione:', $response->text);
		$this->assertTrue($response->forceReply);

		$presentation = self::$dao->find(1);
		$this->assertNotNull($presentation);
		$this->assertInstanceOf(Presentation::class, $presentation);

		$this->assertSame('Pippo', $presentation->firstName);
	}
}
